<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Atelier;
use App\Models\User;
use App\Models\Inscription;
use App\Models\Inscription_event;
use App\Mail\InscriptionVerificationCode;
use App\Rules\UniqueInscriptionPerEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;
use Barryvdh\DomPDF\Facade\Pdf;

class InscriptionController extends Controller
{
    private const VERIFICATION_EXPIRY_MINUTES = 2;

    /**
     * Affiche le formulaire d'inscription public pour un événement.
     */
    public function create(Evenement $evenement)
    {
        $inscriptionView = $this->resolveLandingVariantView($evenement, 'inscription', ['insciption']);
        $eventFullView = $this->resolveLandingVariantView($evenement, 'event-full');

        // Charger les ateliers pour les afficher dans le formulaire
        $evenement->load('ateliers');
        
        // Vérifier si l'événement a une limite de capacité
        if ($evenement->capacite) {
            // Compter le nombre d'inscriptions confirmées pour cet événement
            $registeredCount = Inscription_event::where('id_event', $evenement->id_event)
                ->whereHas('inscription', function ($query) {
                    $query->whereIn('statut', ['validée', 'en_attente']);
                })
                ->count();
            
            // Si la capacité est atteinte
            if ($registeredCount >= $evenement->capacite) {
                return view($eventFullView, compact('evenement'));
            }
        }
        
        // Calculer le nombre d'inscriptions pour chaque atelier
        $atelierCapacities = [];
        foreach ($evenement->ateliers as $atelier) {
            $registeredCount = DB::table('inscription_atelier')
                ->whereIn('id_inscription', function ($query) {
                    $query->select('id_inscription')
                        ->from('inscriptions')
                        ->whereIn('statut', ['validée', 'en_attente']);
                })
                ->where('id_atelier', $atelier->id_atelier)
                ->count();
            $atelierCapacities[$atelier->id_atelier] = $registeredCount;
        }
        
        $socialPrefill = session('inscription_social');
        return view($inscriptionView, compact('evenement', 'socialPrefill', 'atelierCapacities'));
    }

    private function resolveLandingVariantView(Evenement $evenement, string $variant, array $alternatives = []): string
    {
        $templateKey = strtolower((string) ($evenement->landing_template ?: 'template_1'));
        $templateConfig = Evenement::LANDING_TEMPLATES[$templateKey] ?? Evenement::LANDING_TEMPLATES['template_1'];
        $baseView = (string) ($templateConfig['view'] ?? Evenement::LANDING_TEMPLATES['template_1']['view']);
        $baseView = preg_replace('/\.index$/', '', $baseView) ?: 'landing.Template 1';

        $candidateVariants = array_values(array_unique(array_merge([$variant], $alternatives)));
        $candidates = [];

        foreach ($candidateVariants as $candidateVariant) {
            $candidates[] = $baseView . '.' . $candidateVariant;
        }

        if ($templateKey !== 'template_1') {
            foreach ($candidateVariants as $candidateVariant) {
                $candidates[] = 'landing.Template 1.' . $candidateVariant;
            }
        }

        if ($variant === 'event-full') {
            $candidates[] = 'landing.Template 4.event-full';
        }

        $candidates[] = 'landing.' . $variant;

        foreach ($candidates as $candidate) {
            if (view()->exists($candidate)) {
                return $candidate;
            }
        }

        return $candidates[0];
    }

    /**
     * Redirige vers Google/Facebook pour pre-remplir l'inscription.
     */
    public function redirectToProvider(Evenement $evenement, string $provider)
    {
        if (!in_array($provider, ['google', 'facebook'], true)) {
            return redirect()->route('inscription.create', $evenement)->with('error', 'Provider social non supporte.');
        }

        session(['inscription_social_event' => $evenement->id_event]);

        $driver = Socialite::driver($provider);
        if ($provider === 'facebook') {
            $driver = $driver->scopes(['email']);
        }

        return $driver->redirect();
    }

    /**
     * Retour OAuth Google/Facebook.
     */
    public function handleProviderCallback(string $provider)
    {
        $eventId = session('inscription_social_event');
        $evenement = Evenement::find($eventId);
        if (!$evenement) {
            return redirect('/')->with('error', "Evenement introuvable apres authentification $provider.");
        }

        if (!in_array($provider, ['google', 'facebook'], true)) {
            return redirect()->route('inscription.create', $evenement)->with('error', 'Provider social non supporte.');
        }

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $email = strtolower((string) $socialUser->getEmail());

            if (!$email) {
                return redirect()->route('inscription.create', $evenement)->with('error', "Aucun email renvoye par $provider.");
            }

            $fullName = trim((string) ($socialUser->getName() ?: $socialUser->getNickname() ?: 'Participant'));
            $parts = preg_split('/\s+/', $fullName, 2) ?: [];
            $prenom = $parts[0] ?? 'Participant';
            $nom = $parts[1] ?? 'Participant';

            session([
                'inscription_social' => [
                    'provider' => $provider,
                    'email' => $email,
                    'prenom' => $prenom,
                    'nom' => $nom,
                ],
            ]);

            return redirect()->route('inscription.create', $evenement)->with('success', "Compte $provider connecte. Completez le formulaire.");
        } catch (\Throwable $e) {
            Log::warning('OAuth inscription callback error: ' . $e->getMessage());
            return redirect()->route('inscription.create', $evenement)->with('error', "Connexion $provider impossible. Reessayez.");
        }
    }

    /**
     * Enregistre une nouvelle inscription depuis le formulaire public.
     */
    public function store(Request $request, Evenement $evenement)
    {
        $socialData = session('inscription_social');
        $isSocialFlow = is_array($socialData)
            && ($request->input('social_provider') === ($socialData['provider'] ?? null))
            && (strtolower((string) $request->input('email')) === strtolower((string) ($socialData['email'] ?? '')));

        // Validation de base (toujours requis)
        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                new UniqueInscriptionPerEvent($evenement->id_event),
            ],
            'telephone' => 'required|string|max:20',
            'password' => $isSocialFlow ? 'nullable|string|min:6' : 'required|string|min:6',
            'company' => 'nullable|string|max:255',
            'one_to_one' => 'nullable|boolean',
            'ateliers' => 'nullable|array',
            'ateliers.*' => [
                'integer',
                Rule::exists('ateliers', 'id_atelier')->where(fn ($query) => $query->where('id_event', $evenement->id_event)),
            ],
        ];

        // Si one-to-one est cochÃƒÂ©, ajouter les validations supplÃƒÂ©mentaires
        if ($request->has('one_to_one') && $request->one_to_one) {
            $rules['photo'] = 'required|image|mimes:jpeg,png,jpg,gif|max:4096';
            $rules['presentation'] = 'required|string|max:1000';
            $rules['poste'] = 'required|string|max:255';
            $rules['lien_linkedin'] = 'required|string|max:255';
            $rules['objectif'] = 'required|string|max:1000';
        }

        $data = $request->validate($rules);

        try {
            // Vérifier la capacité des ateliers sélectionnés
            if (!empty($data['ateliers'])) {
                $ateliers = Atelier::whereIn('id_atelier', $data['ateliers'])->get();
                
                foreach ($ateliers as $atelier) {
                    if ($atelier->capacite) {
                        // Compter le nombre d'inscriptions pour cet atelier (validées ou en attente)
                        $registeredCount = DB::table('inscription_atelier')
                            ->whereIn('id_inscription', function ($query) {
                                $query->select('id_inscription')
                                    ->from('inscriptions')
                                    ->whereIn('statut', ['validée', 'en_attente']);
                            })
                            ->where('id_atelier', $atelier->id_atelier)
                            ->count();
                        
                        if ($registeredCount >= $atelier->capacite) {
                            return back()->withInput()->withErrors([
                                'ateliers' => "L'atelier \"{$atelier->titre}\" n'a plus de places disponibles."
                            ]);
                        }
                    }
                }
            }

            DB::beginTransaction();

            // Vérification supplémentaire: s'assurer que l'email n'est pas déjà inscrit à cet événement
            $emailLower = strtolower($data['email']);
            $existingUser = User::where('email', $emailLower)->first();
            if ($existingUser) {
                $alreadyRegistered = Inscription_event::where('id_user', $existingUser->id_user)
                    ->where('id_event', $evenement->id_event)
                    ->exists();
                    
                if ($alreadyRegistered) {
                    DB::rollBack();
                    return back()->withInput()->withErrors([
                        'email' => "Cet email est déjà inscrit à cet événement."
                    ]);
                }
            }

            // 1. CrÃƒÂ©er ou rÃƒÂ©cupÃƒÂ©rer l'utilisateur (participant)
            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                $user = User::create([
                    'nom' => $data['nom'],
                    'prenom' => $data['prenom'],
                    'email' => $data['email'],
                    'telephone' => $data['telephone'],
                    'password' => Hash::make($data['password'] ?? Str::random(24)),
                    'role' => 'participant',
                ]);
            } else {
                $user->nom = $data['nom'];
                $user->prenom = $data['prenom'];
                $user->telephone = $data['telephone'];
                $user->save();
            }

            $inscriptionId = null;

            // 2. Si one-to-one est cochÃƒÂ©, crÃƒÂ©er une inscription complÃƒÂ¨te
            if ($request->has('one_to_one') && $request->one_to_one) {
                
                // GÃƒÂ©rer l'upload de la photo
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store('photos', 'public');
                }

                // CrÃƒÂ©er l'inscription avec les informations complÃƒÂ©mentaires
                $inscription = Inscription::create([
                    'id_user' => $user->id_user,
                    'date_ins' => now(),
                    'company' => $data['company'] ?? null,
                    'photo' => $photoPath,
                    'presentation' => $data['presentation'],
                    'poste' => $data['poste'],
                    'lien_linkedin' => $data['lien_linkedin'],
                    'objectif' => $data['objectif'],
                ]);

                $inscriptionId = $inscription->id_inscription;
            } else {
                // 3. Si one-to-one n'est pas cochÃƒÂ©, crÃƒÂ©er une inscription basique (minimale)
                $inscription = Inscription::create([
                    'id_user' => $user->id_user,
                    'date_ins' => now(),
                    'company' => $data['company'] ?? null,
                    'photo' => null,
                    'presentation' => null,
                    'poste' => null,
                    'lien_linkedin' => null,
                    'objectif' => null,
                ]);

                $inscriptionId = $inscription->id_inscription;
            }

            // 4. Lier l'inscription à l'événement dans inscription_event
            Inscription_event::create([
                'id_inscription' => $inscriptionId,
                'id_event' => $evenement->id_event,
                'id_user' => $user->id_user,
            ]);

            // 5. Ajouter les ateliers sÃƒÂ©lectionnÃƒÂ©s
            if ($request->has('ateliers') && is_array($request->ateliers)) {
                $inscription->ateliers()->attach($request->ateliers);
            }

            DB::commit();

            // Envoyer un code de verification par email
            $this->issueVerificationCode($inscription, 'email');

            // Stocker l'ID d'inscription et l'email dans la session
            session([
                'inscription_id' => $inscription->id_inscription,
                'inscription_email' => $user->email,
            ]);
            session()->forget('inscription_social');
            session()->forget('inscription_social_event');

            if ($this->otpColumnsAvailable()) {
                // Rediriger vers la page de verification
                return redirect()->route('inscription.verify.form', $inscription)
                    ->with('success', 'Un code de verification a ete envoye a votre email.');
            }

            // Fallback si la migration OTP n'est pas encore appliquee
            $inscription->statut = 'validÃƒÂ©e';
            $inscription->save();
            return redirect()->route('inscription.confirmation', $inscription)
                ->with('success', 'Inscription enregistree. Verification OTP indisponible temporairement.');

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            Log::error('Erreur dÃƒÂ©taillÃƒÂ©e d\'inscription: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Une erreur est survenue lors de votre inscription. Veuillez rÃƒÂ©essayer.');
        }
    }

    /**
     * Affiche la page de confirmation aprÃƒÂ¨s une inscription rÃƒÂ©ussie.
     */
    public function showVerificationForm(Inscription $inscription)
    {
        if (!$this->otpColumnsAvailable()) {
            return redirect()->route('inscription.confirmation', $inscription);
        }

        $inscription->load(['user', 'evenements']);
        $evenement = $inscription->evenements->first();
        return view('inscriptions.verify', compact('inscription', 'evenement'));
    }

    public function verifyCode(Request $request, Inscription $inscription)
    {
        if (!$this->otpColumnsAvailable()) {
            return redirect()->route('inscription.confirmation', $inscription);
        }

        if ($inscription->verified_at || $inscription->statut === 'validÃƒÂ©e') {
            return redirect()->route('inscription.confirmation', $inscription);
        }

        $data = $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if (!$inscription->verification_code || !$inscription->verification_expires_at) {
            return back()->with('error', 'Aucun code actif. Veuillez renvoyer un nouveau code.');
        }

        if (now()->greaterThan($inscription->verification_expires_at)) {
            return back()->with('error', 'Le code a expire. Veuillez renvoyer un nouveau code.');
        }

        if (!Hash::check($data['code'], $inscription->verification_code)) {
            $inscription->verification_attempts = (int) $inscription->verification_attempts + 1;
            $inscription->save();
            return back()->with('error', 'Code incorrect. Veuillez reessayer.');
        }

        $inscription->statut = 'validÃƒÂ©e';
        $inscription->verified_at = now();
        $inscription->verification_code = null;
        $inscription->verification_expires_at = null;
        $inscription->verification_attempts = 0;
        $inscription->save();

        return redirect()->route('inscription.confirmation', $inscription)
            ->with('success', 'Inscription verifiee avec succes.');
    }

    public function resendCode(Inscription $inscription)
    {
        if (!$this->otpColumnsAvailable()) {
            return redirect()->route('inscription.confirmation', $inscription);
        }

        if ($inscription->statut === 'validÃƒÂ©e') {
            return redirect()->route('inscription.confirmation', $inscription);
        }

        $this->issueVerificationCode($inscription, 'email');
        return back()->with('success', 'Un nouveau code a ete envoye par email.');
    }

    public function trySmsVerification(Inscription $inscription)
    {
        if (!$this->otpColumnsAvailable()) {
            return redirect()->route('inscription.confirmation', $inscription);
        }

        if ($inscription->statut === 'validÃƒÂ©e') {
            return redirect()->route('inscription.confirmation', $inscription);
        }

        if (!optional($inscription->user)->telephone) {
            $this->issueVerificationCode($inscription, 'email');
            return back()->with('error', 'Aucun numero de telephone disponible. Nouveau code envoye par email.');
        }

        if (!config('services.sms.enabled', false)) {
            $this->issueVerificationCode($inscription, 'email');
            return back()->with('error', 'SMS indisponible pour le moment. Nouveau code envoye par email.');
        }

        $this->issueVerificationCode($inscription, 'sms');
        Log::info('SMS verification code generated', [
            'id_inscription' => $inscription->id_inscription,
            'phone' => optional($inscription->user)->telephone,
        ]);

        return back()->with('success', 'Code envoye par SMS.');
    }

    private function issueVerificationCode(Inscription $inscription, string $method): void
    {
        if (!$this->otpColumnsAvailable()) {
            Log::warning('Verification columns missing on inscriptions table. Skipping OTP flow.', [
                'id_inscription' => $inscription->id_inscription,
            ]);
            return;
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $inscription->verification_code = Hash::make($code);
        $inscription->verification_expires_at = now()->addMinutes(self::VERIFICATION_EXPIRY_MINUTES);
        $inscription->verification_attempts = 0;
        $inscription->verification_method = $method;
        $inscription->verification_sent_at = now();
        $inscription->save();

        if ($method === 'email' && optional($inscription->user)->email) {
            try {
                Mail::to($inscription->user->email)->send(
                    new InscriptionVerificationCode($inscription, $code, self::VERIFICATION_EXPIRY_MINUTES)
                );
            } catch (\Throwable $e) {
                Log::error('Verification mail send failed', [
                    'id_inscription' => $inscription->id_inscription,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function otpColumnsAvailable(): bool
    {
        return Schema::hasColumn('inscriptions', 'verification_code')
            && Schema::hasColumn('inscriptions', 'verification_expires_at')
            && Schema::hasColumn('inscriptions', 'verification_sent_at')
            && Schema::hasColumn('inscriptions', 'verification_method')
            && Schema::hasColumn('inscriptions', 'verification_attempts');
    }

    public function confirmation(Inscription $inscription)
    {

        $inscription->load(['user', 'evenements']);
        $evenement = $inscription->evenements->first();
        return view('inscriptions.confirmation', compact('inscription', 'evenement'));
    }

    /**
     * Affiche les dÃƒÂ©tails complets d'une inscription.
     */
    public function show(Inscription $inscription)
    {
        $inscription->load(['user', 'evenements', 'ateliers']);
        return view('inscriptions.show', compact('inscription'));
    }

    /**
     * GÃƒÂ©nÃƒÂ¨re et tÃƒÂ©lÃƒÂ©charge le badge du participant en PDF.
     */
    public function downloadBadge(Inscription $inscription)
    {
        $statut = strtolower((string) ($inscription->statut ?? ''));
        $isValidated = str_starts_with($statut, 'valid');

        if (!$inscription->verified_at && !$isValidated) {
            return redirect()->route('inscription.verify.form', $inscription)
                ->with('error', "Badge disponible apres verification de l'inscription.");
        }
        try {
            // Charger les relations nÃƒÂ©cessaires
            $inscription->load(['user', 'evenements', 'ateliers', 'evenements.entreprise']);

            // PrÃƒÂ©parer le contenu du QR : utiliser une donnÃƒÂ©e existante ou un lien public de confirmation
            $qrData = $inscription->qr_code_data ?? route('inscription.confirmation', $inscription->id_inscription);

            // GÃƒÂ©nÃƒÂ©rer le QR code en SVG (encodÃƒÂ© en base64) pour ÃƒÂ©viter la dÃƒÂ©pendance Imagick
            $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($qrData);

            $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
            $eventImageDataUri = $this->buildEventImageDataUri($inscription->evenements->first());

            // Charger la vue du badge A6
            $pdf = Pdf::loadView('pdf.badge', [
                'inscription' => $inscription,
                'qrCode' => $qrCode,
                'eventImageDataUri' => $eventImageDataUri,
            ]);

            // Nom de fichier basÃƒÂ© sur le nom de l'utilisateur
            $filename = 'badge-' . Str::slug($inscription->user->name ?? ('inscription-' . $inscription->id_inscription)) . '.pdf';
            return $pdf->download($filename);
        } catch (\Throwable $e) {
            \Log::error('Badge download failed', [
                'inscription_id' => $inscription->id_inscription,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('inscription.confirmation', $inscription)
                ->with('error', 'Le badge n\'a pas pu etre genere. Veuillez reessayer.');
        }
    }

    /**
     * Permet de tÃƒÂ©lÃƒÂ©charger la plaquette de l'ÃƒÂ©vÃƒÂ©nement via la page de confirmation.
     */
    public function downloadPlaquette(Inscription $inscription)
    {
        $evenement = $inscription->evenement;

        // On appelle la mÃƒÂ©thode dÃƒÂ©jÃƒÂ  existante sur EvenementController
        $eventController = new EvenementController();
        return $eventController->downloadPlaquette($evenement);
    }

    /**
     * Valide une ou plusieurs inscriptions (rÃƒÂ©servÃƒÂ© aux admin_entreprise)
     */

    private function buildEventImageDataUri($evenement): ?string
    {
        if (!$evenement || empty($evenement->image)) {
            return null;
        }

        $maxBytes = 2 * 1024 * 1024;

        $path = (string) $evenement->image;
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#^(/)?(storage/|public/|storage/app/public/)#', '', $path);

        if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            $absolutePath = \Illuminate\Support\Facades\Storage::disk('public')->path($path);
            if (is_file($absolutePath) && filesize($absolutePath) > $maxBytes) {
                return null;
            }
            return $this->fileToDataUri($absolutePath);
        }

        if (is_file($evenement->image)) {
            if (filesize((string) $evenement->image) > $maxBytes) {
                return null;
            }
            return $this->fileToDataUri((string) $evenement->image);
        }

        return null;
    }

    private function fileToDataUri(string $absolutePath): ?string
    {
        if (!is_file($absolutePath)) {
            return null;
        }

        $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => null,
        };

        if (!$mime) {
            return null;
        }

        $raw = @file_get_contents($absolutePath);
        if ($raw === false) {
            return null;
        }

        return 'data:' . $mime . ';base64,' . base64_encode($raw);
    }
    public function valider(Request $request)
    {
        $user = auth()->user();
        
        // VÃƒÂ©rifier que l'utilisateur est un collaborateur avec le rÃƒÂ´le admin_entreprise
        $collab = $user->collaborateurs()->first();
        if (!$collab || $collab->role !== 'admin_entreprise') {
            return back()->with('error', 'Vous n\'avez pas la permission de valider les inscriptions.');
        }

        // Valider la requÃƒÂªte
        $request->validate([
            'inscription_ids' => 'nullable|array',
            'inscription_ids.*' => 'integer|exists:inscriptions,id_inscription',
            'evenement_id' => 'required|integer|exists:evenements,id_event',
        ]);

        $evenementId = $request->input('evenement_id');

        // VÃƒÂ©rifier que l'utilisateur a accÃƒÂ¨s ÃƒÂ  cet ÃƒÂ©vÃƒÂ©nement
        $evenement = Evenement::findOrFail($evenementId);
        if ($evenement->id_entreprise !== $collab->id_entreprise) {
            return back()->with('error', 'Vous n\'avez pas accÃƒÂ¨s ÃƒÂ  cet ÃƒÂ©vÃƒÂ©nement.');
        }

        try {
            DB::beginTransaction();

            $action = $request->input('action', 'selected');
            
            if ($action === 'all') {
                // Valider toutes les inscriptions non validÃƒÂ©es
                $count = Inscription::whereIn('id_inscription', 
                    $evenement->inscriptions->pluck('id_inscription')->toArray()
                )
                ->where('statut', '!=', 'validÃƒÂ©e')
                ->update(['statut' => 'validÃƒÂ©e']);
            } else {
                // Valider les inscriptions sÃƒÂ©lectionnÃƒÂ©es
                $inscriptionIds = $request->input('inscription_ids', []);
                
                if (empty($inscriptionIds)) {
                    return back()->with('error', 'Veuillez sÃƒÂ©lectionner au moins une inscription.');
                }

                $count = Inscription::whereIn('id_inscription', $inscriptionIds)
                    ->where('statut', '!=', 'validÃƒÂ©e')
                    ->update(['statut' => 'validÃƒÂ©e']);
            }

            DB::commit();

            if ($count === 0) {
                return back()->with('info', 'Aucune inscription ÃƒÂ  valider (toutes sont dÃƒÂ©jÃƒÂ  validÃƒÂ©es).');
            }

            return back()->with('success', ($count === 1) 
                ? 'L\'inscription a ÃƒÂ©tÃƒÂ© validÃƒÂ©e avec succÃƒÂ¨s.' 
                : $count . ' inscriptions ont ÃƒÂ©tÃƒÂ© validÃƒÂ©es avec succÃƒÂ¨s.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la validation des inscriptions: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la validation.');
        }
    }

    /**
     * Exporte les inscriptions d'un ÃƒÂ©vÃƒÂ©nement en CSV
     */
    public function exportCsv($evenementId)
    {
        // VÃƒÂ©rifier l'authentification
        if (!auth()->check()) {
            return back()->with('error', 'Vous devez ÃƒÂªtre connectÃƒÂ©.');
        }

        $user = auth()->user();
        $evenement = Evenement::findOrFail($evenementId);

        // VÃƒÂ©rifier que l'utilisateur a accÃƒÂ¨s ÃƒÂ  cet ÃƒÂ©vÃƒÂ©nement
        if ($user->role !== 'super_admin') {
            $collab = $user->collaborateurs()->first();
            if (!$collab || $evenement->id_entreprise !== $collab->id_entreprise) {
                return back()->with('error', 'Vous n\'avez pas accÃƒÂ¨s ÃƒÂ  cet ÃƒÂ©vÃƒÂ©nement.');
            }
        }

        // RÃƒÂ©cupÃƒÂ©rer les inscriptions
        $inscriptions = $evenement->inscriptions()->with('user')->get();

        // CrÃƒÂ©er le fichier CSV
        $filename = 'inscriptions-' . $evenement->titre . '-' . now()->format('Y-m-d') . '.csv';
        
        $headers = array(
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function() use ($inscriptions) {
            $file = fopen('php://output', 'w');
            
            // BOM pour UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // En-tÃƒÂªtes du CSV
            fputcsv($file, [
                'Participant',
                'PrÃƒÂ©nom',
                'Nom',
                'Email',
                'Entreprise',
                'Poste',
                'LinkedIn',
                'PrÃƒÂ©sentation',
                'Objectif',
                'Date d\'inscription',
                'Statut'
            ], ';');

            // DonnÃƒÂ©es des inscriptions
            foreach ($inscriptions as $inscription) {
                fputcsv($file, [
                    $inscription->user->name ?? 'N/A',
                    $inscription->user->prenom ?? 'N/A',
                    $inscription->user->nom ?? 'N/A',
                    $inscription->user->email ?? 'N/A',
                    $inscription->company ?? '-',
                    $inscription->poste ?? '-',
                    $inscription->lien_linkedin ?? '-',
                    $inscription->presentation ?? '-',
                    $inscription->objectif ?? '-',
                    $inscription->date_ins ? \Carbon\Carbon::parse($inscription->date_ins)->format('d/m/Y H:i') : '-',
                    ucfirst($inscription->statut ?? 'en_attente'),
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Annule une inscription.
     */
    public function cancel(Inscription $inscription)
    {
        // On pourrait demander une confirmation ou un motif
        $inscription->statut = 'annulÃƒÂ©e';
        $inscription->save();

        // Rediriger vers une page informant que l'annulation est rÃƒÂ©ussie
        return view('inscriptions.cancelled', [
            'evenement' => $inscription->evenement
        ]);
    }
}


