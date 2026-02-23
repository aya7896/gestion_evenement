<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Collaborateur;
use App\Models\Partenaire;
use App\Models\Speaker;
use App\Models\Atelier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreEvenementRequest;
use App\Http\Requests\UpdateEvenementRequest;

class EvenementController extends Controller
{
    /**
     * GÃƒÂ¨re l'inscription publique ÃƒÂ  un ÃƒÂ©vÃƒÂ©nement (nom, email)
     */
    public function publicInscription(Evenement $evenement)
    {
        request()->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
        // Ici, on peut enregistrer l'inscription dans une table dÃƒÂ©diÃƒÂ©e ou envoyer un email
        // Pour la dÃƒÂ©mo, on stocke dans la session
        session()->flash('success', "Inscription enregistrÃƒÂ©e ! Merci pour votre participation.");
        return redirect()->route('public.evenement.landing', $evenement);
    }

    /**
     * Affiche la landing page publique d'un ÃƒÂ©vÃƒÂ©nement (partage)
     */
    public function publicLanding(Evenement $evenement)
    {
        // Recharger les données fraîches pour éviter les problèmes de cache
        // Cela assure que les nouvelles relations sont visibles immédiatement
        $evenement = $evenement->fresh([
            'entreprise',
            'ateliers.speakers',
            'partenaires' => fn ($q) => $q->where('actif', true)->orderBy('ordre')->orderBy('nom'),
        ]);
        $this->normalizeEvenementImage($evenement);
        $templateView = $this->resolveLandingTemplateView($evenement);
        return view($templateView, compact('evenement'));
    }

    public function previewLandingTemplate(string $template)
    {
        $templateKey = strtolower($template);
        if (!array_key_exists($templateKey, Evenement::LANDING_TEMPLATES)) {
            abort(404);
        }

        $evenement = new Evenement([
            'slug' => 'preview-event',
            'titre' => 'Demo Event',
            'description' => 'Visualisation du template landing pour selection admin.',
            'type' => 'conference',
            'localisation' => 'Casablanca',
            'lieu' => 'Conference Hall',
            'date_heure_debut' => now()->addDays(20),
            'date_heure_fin' => now()->addDays(20)->addHours(5),
            'mode' => 'hybride',
            'capacite' => 250,
            'visibility' => 'public',
            'status' => 'active',
            'landing_template' => $templateKey,
            'landing_content' => [
                'hero_title' => 'Demo Event',
                'hero_subtitle' => 'Visualisation du template landing pour selection admin.',
                'primary_cta_text' => 'Je m inscris',
                'secondary_cta_text' => 'Voir le programme',
            ],
        ]);

        $evenement->setRelation('entreprise', new \App\Models\Entreprise(['nom' => 'Demo Company']));
        $evenement->setRelation('ateliers', collect());
        $evenement->setRelation('partenaires', collect());

        $templateView = Evenement::LANDING_TEMPLATES[$templateKey]['view'];

        return view($templateView, compact('evenement'));
    }
    /**
     * VÃƒÂ©rifie si l'utilisateur est super admin
     */
    private function isSuperAdmin()
    {
        return Auth::user()->role === 'super_admin';
    }

    /**
     * RÃƒÂ©cupÃƒÂ¨re l'entreprise de l'admin connectÃƒÂ© (si admin_entreprise)
     */
    private function getUserEntrepriseId()
    {
        if ($this->isSuperAdmin()) {
            return null; // Super admin voit tout
        }

        $collab = Auth::user()->collaborateurs()->first();
        return $collab ? $collab->id_entreprise : null;
    }

    /**
     * RÃƒÂ©cupÃƒÂ¨re le collaborateur connectÃƒÂ©
     */
    private function getUserCollaborateur()
    {
        return Auth::user()->collaborateurs()->first();
    }

    public function index()
    {
        // Super Admin voit tous les ÃƒÂ©vÃƒÂ©nements
        // Admin Entreprise voit uniquement les ÃƒÂ©vÃƒÂ©nements de son entreprise
        if ($this->isSuperAdmin()) {
            $evenements = Evenement::with(['ateliers', 'entreprise'])->get();
        } else {
            $entrepriseId = $this->getUserEntrepriseId();
            if (!$entrepriseId) {
                abort(403, 'Aucune entreprise associée');
            }

            $evenements = Evenement::where('id_entreprise', $entrepriseId)
                                   ->with(['ateliers', 'entreprise'])
                                   ->get();
        }

        return view('evenements.index', compact('evenements'));
    }

    public function create()
    {
        // Super Admin ne peut plus crÃƒÂ©er d'ÃƒÂ©vÃƒÂ©nements
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas crÃƒÂ©er d\'ÃƒÂ©vÃƒÂ©nement.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise') {
            abort(403, 'Seuls les administrateurs peuvent crÃƒÂ©er des ÃƒÂ©vÃƒÂ©nements');
        }
        // admin_entreprise crÃƒÂ©e pour sa propre entreprise (pas d'option ÃƒÂ  choisir)
        $entreprises = null;
        $partenaires = Partenaire::where('actif', true)->orderBy('ordre')->orderBy('nom')->get();
        $speakers = Speaker::where('actif', true)->orderBy('nom')->orderBy('prenom')->get();
        $partenaireTypes = Partenaire::TYPES;
        $landingTemplates = Evenement::LANDING_TEMPLATES;
        return view('evenements.create', compact('entreprises', 'partenaires', 'speakers', 'partenaireTypes', 'landingTemplates'));
    }

    public function store(StoreEvenementRequest $request)
    {
        // Super Admin ne peut plus crÃƒÂ©er d'ÃƒÂ©vÃƒÂ©nements
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas crÃƒÂ©er d\'ÃƒÂ©vÃƒÂ©nement.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise') {
            abort(403, 'Seuls les administrateurs peuvent crÃƒÂ©er des ÃƒÂ©vÃƒÂ©nements');
        }
        $request->validate([
            'partenaires' => 'nullable|array',
            'partenaires.*' => 'integer|exists:partenaires,id_partenaire',
            'new_partenaires' => 'nullable|array',
            'new_partenaires.*.nom' => 'nullable|string|max:255',
            'new_partenaires.*.type' => 'nullable|in:gold,silver,bronze,media,institutionnel,autre',
            'new_partenaires.*.email' => 'nullable|email|max:255',
            'new_partenaires.*.telephone' => 'nullable|string|max:50',
            'new_partenaires.*.site_web' => 'nullable|url|max:255',
            'new_partenaires.*.description' => 'nullable|string',
            'new_partenaires.*.logo' => 'nullable|image|max:2048',
            'ateliers' => 'nullable|array',
            'ateliers.*.titre' => 'nullable|string|max:255',
            'ateliers.*.date' => 'nullable|date',
            'ateliers.*.heure_debut' => 'nullable|date_format:H:i',
            'ateliers.*.heure_fin' => 'nullable|date_format:H:i',
            'ateliers.*.capacite' => 'nullable|integer|min:1',
            'ateliers.*.sujet' => 'nullable|string',
            'ateliers.*.banniere' => 'nullable|image|max:2048',
            'ateliers.*.speakers' => 'nullable|array',
            'ateliers.*.speakers.*' => 'integer|exists:speakers,id_speaker',
            'ateliers.*.new_speaker.nom' => 'nullable|string|max:255',
            'ateliers.*.new_speaker.prenom' => 'nullable|string|max:255',
            'ateliers.*.new_speaker.email' => 'nullable|email|max:255',
            'ateliers.*.new_speaker.poste' => 'nullable|string|max:255',
            'ateliers.*.new_speaker.company' => 'nullable|string|max:255',
            'ateliers.*.new_speaker.bio' => 'nullable|string',
            'ateliers.*.new_speaker.photo' => 'nullable|image|max:2048',
        ]);
        $plaquettePath = $request->plaquette_pdf ? $request->plaquette_pdf->store('plaquettes') : null;
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
        $validated = $request->validated();
        $validated['landing_template'] = $validated['landing_template'] ?? 'template_1';
        $validated['landing_content'] = array_filter($validated['landing_content'] ?? [], fn ($value) => filled($value));

        $evenement = null;
        DB::transaction(function () use ($request, $validated, $collab, $plaquettePath, $imagePath, &$evenement) {
            $evenement = Evenement::create(array_merge($validated, [
                'id_Collaborateur' => $collab->id_Collaborateur,
                'id_entreprise' => $collab->id_entreprise,
                'plaquette_pdf' => $plaquettePath,
                'image' => $imagePath,
            ]));

            $partenaireIds = collect($request->input('partenaires', []))
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
                ->all();
            $evenement->partenaires()->sync($partenaireIds);

            foreach ((array) $request->input('new_partenaires', []) as $index => $newPartenaire) {
                $hasName = filled($newPartenaire['nom'] ?? null);
                if (!$hasName) {
                    continue;
                }

                $logoPath = $request->hasFile("new_partenaires.$index.logo")
                    ? $request->file("new_partenaires.$index.logo")->store('partenaires/logos', 'public')
                    : null;

                $partenaire = Partenaire::create([
                    'nom' => trim((string) ($newPartenaire['nom'] ?? '')),
                    'type' => (string) ($newPartenaire['type'] ?? 'autre'),
                    'email' => filled($newPartenaire['email'] ?? null) ? trim((string) $newPartenaire['email']) : null,
                    'telephone' => filled($newPartenaire['telephone'] ?? null) ? trim((string) $newPartenaire['telephone']) : null,
                    'description' => filled($newPartenaire['description'] ?? null) ? trim((string) $newPartenaire['description']) : null,
                    'site_web' => filled($newPartenaire['site_web'] ?? null) ? trim((string) $newPartenaire['site_web']) : null,
                    'logo' => $logoPath,
                    'actif' => true,
                ]);

                $evenement->partenaires()->syncWithoutDetaching([$partenaire->id_partenaire]);
            }

            foreach ((array) $request->input('ateliers', []) as $atelierIndex => $atelierData) {
                $hasCoreData = filled($atelierData['titre'] ?? null)
                    || filled($atelierData['date'] ?? null)
                    || filled($atelierData['heure_debut'] ?? null)
                    || filled($atelierData['heure_fin'] ?? null)
                    || filled($atelierData['capacite'] ?? null);

                if (!$hasCoreData) {
                    continue;
                }

                if (!filled($atelierData['titre'] ?? null) || !filled($atelierData['date'] ?? null) || !filled($atelierData['heure_debut'] ?? null) || !filled($atelierData['heure_fin'] ?? null) || !filled($atelierData['capacite'] ?? null)) {
                    continue;
                }

                $atelier = Atelier::create([
                    'id_event' => $evenement->id_event,
                    'titre' => trim((string) ($atelierData['titre'] ?? '')),
                    'date' => $atelierData['date'],
                    'heure_debut' => $atelierData['heure_debut'],
                    'heure_fin' => $atelierData['heure_fin'],
                    'capacite' => (int) $atelierData['capacite'],
                    'sujet' => filled($atelierData['sujet'] ?? null) ? trim((string) $atelierData['sujet']) : null,
                    'banniere' => $request->hasFile("ateliers.$atelierIndex.banniere")
                        ? $request->file("ateliers.$atelierIndex.banniere")->store('bannieres', 'public')
                        : null,
                ]);

                $speakerIds = collect((array) ($atelierData['speakers'] ?? []))
                    ->map(fn ($id) => (int) $id)
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                $newSpeakerData = (array) ($atelierData['new_speaker'] ?? []);
                $hasNewSpeaker = filled($newSpeakerData['nom'] ?? null) && filled($newSpeakerData['prenom'] ?? null);

                if ($hasNewSpeaker) {
                    $photoPath = $request->hasFile("ateliers.$atelierIndex.new_speaker.photo")
                        ? $request->file("ateliers.$atelierIndex.new_speaker.photo")->store('speakers/photos', 'public')
                        : null;

                    $speaker = Speaker::create([
                        'nom' => trim((string) ($newSpeakerData['nom'] ?? '')),
                        'prenom' => trim((string) ($newSpeakerData['prenom'] ?? '')),
                        'email' => filled($newSpeakerData['email'] ?? null) ? trim((string) $newSpeakerData['email']) : null,
                        'poste' => filled($newSpeakerData['poste'] ?? null) ? trim((string) $newSpeakerData['poste']) : null,
                        'company' => filled($newSpeakerData['company'] ?? null) ? trim((string) $newSpeakerData['company']) : null,
                        'bio' => filled($newSpeakerData['bio'] ?? null) ? trim((string) $newSpeakerData['bio']) : null,
                        'photo' => $photoPath,
                        'actif' => true,
                    ]);

                    $speakerIds[] = (int) $speaker->id_speaker;
                    $speakerIds = array_values(array_unique($speakerIds));
                }

                if (!empty($speakerIds)) {
                    $syncData = collect($speakerIds)->mapWithKeys(
                        fn ($id) => [(int) $id => ['role' => 'speaker', 'ordre' => 0]]
                    )->all();
                    $atelier->speakers()->sync($syncData);
                }
            }
        });
    
        // Redirection pour l'admin d'entreprise
        if ($collab->role === 'admin_entreprise') {
            return redirect()->route('admin.evenements.index')->with('success', 'Événement créé avec succès');
        }
    
        return redirect()->route('evenements.index')->with('success', 'Événement créé avec succès');
    }

    public function show(Evenement $evenement)
    {
        // VÃƒÂ©rifier l'accÃƒÂ¨ss pour Admin Entreprise
        if (!$this->isSuperAdmin()) {
            $entrepriseId = $this->getUserEntrepriseId();
            if ($evenement->id_entreprise !== $entrepriseId) {
                abort(403, 'Vous ne pouvez voir que les ÃƒÂ©vÃƒÂ©nements de votre entreprise');
            }
        }

        // Load related data used by the view
        $evenement->load(['ateliers', 'entreprise', 'inscriptions.user', 'partenaires']);
        $availablePartenaires = Partenaire::where('actif', true)
            ->orderBy('ordre')
            ->orderBy('nom')
            ->get();

        $this->normalizeEvenementImage($evenement);
        return view('evenements.show', compact('evenement', 'availablePartenaires'));
    }

    public function attachPartenaire(Request $request, Evenement $evenement)
    {
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas modifier les partenaires.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise' || $evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403, 'Vous ne pouvez modifier que les partenaires de vos Ã©vÃ©nements.');
        }

        $validated = $request->validate([
            'id_partenaire' => 'required|exists:partenaires,id_partenaire',
            'contribution' => 'nullable|string',
            'montant' => 'nullable|numeric|min:0',
        ]);

        $evenement->partenaires()->syncWithoutDetaching([
            (int) $validated['id_partenaire'] => [
                'contribution' => $validated['contribution'] ?? null,
                'montant' => $validated['montant'] ?? null,
            ],
        ]);

        return redirect()->route('evenements.show', $evenement)->with('success', 'Sponsor ajoutÃ© Ã  l\'Ã©vÃ©nement.');
    }
    public function createAndAttachPartenaire(Request $request, Evenement $evenement)
    {
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas créer de partenaires.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise' || $evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403, 'Vous ne pouvez modifier que les partenaires de vos événements.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:gold,silver,bronze,media,institutionnel,autre',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'site_web' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'contribution' => 'nullable|string',
            'montant' => 'nullable|numeric|min:0',
        ]);

        // Create the partenaire
        $partenaireData = [
            'nom' => $validated['nom'],
            'type' => $validated['type'],
            'email' => $validated['email'] ?? null,
            'telephone' => $validated['telephone'] ?? null,
            'description' => $validated['description'] ?? null,
            'site_web' => $validated['site_web'] ?? null,
            'actif' => true,
            'ordre' => 0,
        ];

        if ($request->hasFile('logo')) {
            $partenaireData['logo'] = $request->file('logo')->store('partenaires/logos', 'public');
        }

        $partenaire = Partenaire::create($partenaireData);

        // Attach to event using attach() method
        $evenement->partenaires()->attach($partenaire->id_partenaire, [
            'contribution' => $validated['contribution'] ?? null,
            'montant' => $validated['montant'] ?? null,
        ]);

        return redirect()->route('evenements.show', $evenement)->with('success', 'Sponsor créé et ajouté à l\'événement.');
    }
    public function detachPartenaire(Evenement $evenement, Partenaire $partenaire)
    {
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas modifier les partenaires.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise' || $evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403, 'Vous ne pouvez modifier que les partenaires de vos événements.');
        }

        $evenement->partenaires()->detach($partenaire->id_partenaire);
        return redirect()->route('evenements.show', $evenement)->with('success', 'Sponsor retiré de l\'événement.');
    }

    public function toggleVisibility(Evenement $evenement)
    {
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas modifier la visibilité.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise' || $evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403, 'Vous ne pouvez modifier que la visibilité de vos événements.');
        }

        $evenement->visibility = $evenement->visibility === 'public' ? 'private' : 'public';
        $evenement->save();

        $message = $evenement->visibility === 'public' ? 'Événement rendu visible.' : 'Événement caché.';
        return redirect()->route('evenements.show', $evenement)->with('success', $message);
    }

    /**
     * Ensure event image points to a valid public storage path.
     */
    private function normalizeEvenementImage(Evenement $evenement): void
    {
        if (!$evenement->image) {
            return;
        }

        $img = preg_replace('#^(/)?(storage/|public/|storage/app/public/)#', '', (string) $evenement->image);

        if ($img && \Storage::disk('public')->exists($img)) {
            if ($evenement->image !== $img) {
                $evenement->image = $img;
                $evenement->save();
            }
            return;
        }

        // Handle legacy absolute path (Windows or Unix) by copying it to public storage.
        if (file_exists($evenement->image)) {
            try {
                $newPath = 'images/' . uniqid() . '_' . basename($evenement->image);
                \Storage::disk('public')->put($newPath, file_get_contents($evenement->image));
                $evenement->image = $newPath;
                $evenement->save();
                return;
            } catch (\Throwable $e) {
                // keep falling through
            }
        }

        // Invalid path: clear it to avoid broken references.
        $evenement->image = null;
        $evenement->save();
    }

    /**
     * Download existing plaquette PDF or generate one on-the-fly including event image.
     */
        public function downloadPlaquette(Evenement $evenement)
    {
        // VÃƒÂ©rifier l'accÃƒÂ¨ss pour Admin Entreprise
        if (!$this->isSuperAdmin()) {
            $entrepriseId = $this->getUserEntrepriseId();
            if ($evenement->id_entreprise !== $entrepriseId) {
                abort(403, 'Vous ne pouvez tÃƒÂ©lÃƒÂ©charger que les plaquettes de votre entreprise');
            }
        }

        return $this->resolvePlaquetteDownload($evenement);
    }

    /**
     * TÃ©lÃ©chargement public de la plaquette depuis la landing page.
     */
    public function publicDownloadPlaquette(Evenement $evenement)
    {
        if (($evenement->visibility ?? 'public') !== 'public') {
            abort(403, "Cette plaquette n'est pas disponible publiquement.");
        }

        return $this->resolvePlaquetteDownload($evenement);
    }

    /**
     * Resolve the event plaquette file from available storage locations.
     */
    private function resolvePlaquetteDownload(Evenement $evenement)
    {
        // Try to return stored plaquette if available
        if ($evenement->plaquette_pdf) {
            // normally stored in storage/app/plaquettes or storage/app/public/plaquettes
            $possiblePaths = [
                'public/' . $evenement->plaquette_pdf,
                $evenement->plaquette_pdf,
            ];

            foreach ($possiblePaths as $p) {
                // Check on local disk
                if (\Storage::disk('local')->exists($p)) {
                    $realPath = \Storage::disk('local')->path($p);
                    $filename = \Illuminate\Support\Str::slug($evenement->titre) . '_plaquette.pdf';
                    return response()->download($realPath, $filename, ['Content-Type' => 'application/pdf']);
                }

                // Check on public disk (storage/app/public)
                if (\Storage::disk('public')->exists($p)) {
                    $realPath = \Storage::disk('public')->path($p);
                    $filename = \Illuminate\Support\Str::slug($evenement->titre) . '_plaquette.pdf';
                    return response()->download($realPath, $filename, ['Content-Type' => 'application/pdf']);
                }

                // If $p is actually an absolute path stored in DB
                if (file_exists($p)) {
                    $filename = \Illuminate\Support\Str::slug($evenement->titre) . '_plaquette.pdf';
                    return response()->download($p, $filename, ['Content-Type' => 'application/pdf']);
                }
            }
        }

        // If DomPDF is available, generate a simple plaquette with the event image and details
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('evenements.plaquette', compact('evenement'));
            $filename = \Illuminate\Support\Str::slug($evenement->titre) . '_plaquette.pdf';
            return $pdf->download($filename);
        }

        // Fallback: no file and no generator available
        abort(404, "Plaquette non trouvÃƒÂ©e et la gÃƒÂ©nÃƒÂ©ration PDF n'est pas disponible. Installez 'barryvdh/laravel-dompdf' pour activer la gÃƒÂ©nÃƒÂ©ration de PDF.");
    }
    public function edit(Evenement $evenement)
    {
        // Super Admin ne peut plus modifier d'ÃƒÂ©vÃƒÂ©nements
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas modifier d\'ÃƒÂ©vÃƒÂ©nement.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise') {
            abort(403, 'Seuls les administrateurs peuvent modifier des ÃƒÂ©vÃƒÂ©nements');
        }
        // VÃƒÂ©rifier que c'est son entreprise
        if ($evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403, 'Vous ne pouvez modifier que les ÃƒÂ©vÃƒÂ©nements de votre entreprise');
        }
        $evenement->load('partenaires');
        $partenaires = Partenaire::where('actif', true)->orderBy('ordre')->orderBy('nom')->get();
        $landingTemplates = Evenement::LANDING_TEMPLATES;
        return view('evenements.edit', compact('evenement', 'partenaires', 'landingTemplates'));
    }

    public function update(UpdateEvenementRequest $request, Evenement $evenement)
    {
        // Super Admin ne peut plus modifier d'ÃƒÂ©vÃƒÂ©nements
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas modifier d\'ÃƒÂ©vÃƒÂ©nement.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise') {
            abort(403, 'Seuls les administrateurs peuvent modifier des ÃƒÂ©vÃƒÂ©nements');
        }
        // VÃƒÂ©rifier que c'est son entreprise
        if ($evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403, 'Vous ne pouvez modifier que les ÃƒÂ©vÃƒÂ©nements de votre entreprise');
        }
        $request->validate([
            'partenaires' => 'nullable|array',
            'partenaires.*' => 'integer|exists:partenaires,id_partenaire',
        ]);
        $plaquettePath = $request->plaquette_pdf ? $request->plaquette_pdf->store('plaquettes') : $evenement->plaquette_pdf;
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : $evenement->image;
        $validated = $request->validated();
        $validated['landing_template'] = $validated['landing_template'] ?? ($evenement->landing_template ?: 'template_1');
        $validated['landing_content'] = array_filter($validated['landing_content'] ?? [], fn ($value) => filled($value));

        $evenement->update(array_merge($validated, [
            'plaquette_pdf' => $plaquettePath,
            'image' => $imagePath,
        ]));
        $partenaireIds = collect($request->input('partenaires', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
        $evenement->partenaires()->sync($partenaireIds);

        if ($collab->role === 'admin_entreprise') {
            return redirect()->route('admin.evenements.index')->with('success', 'Événement mis à jour avec succès');
        }
    
        return redirect()->route('evenements.index')->with('success', 'Événement mis à jour avec succès');
    }

    private function resolveLandingTemplateView(Evenement $evenement): string
    {
        $template = $evenement->landing_template ?: 'template_1';
        return Evenement::LANDING_TEMPLATES[$template]['view'] ?? Evenement::LANDING_TEMPLATES['template_1']['view'];
    }

    public function destroy(Evenement $evenement)
    {
        // Super Admin ne peut plus supprimer d'ÃƒÂ©vÃƒÂ©nements
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas supprimer d\'ÃƒÂ©vÃƒÂ©nement.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise') {
            abort(403, 'Seuls les administrateurs peuvent supprimer des ÃƒÂ©vÃƒÂ©nements');
        }
        // VÃƒÂ©rifier que c'est son entreprise
        if ($evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403, 'Vous ne pouvez supprimer que les ÃƒÂ©vÃƒÂ©nements de votre entreprise');
        }
        $evenement->delete();
        return redirect()->route('evenements.index')->with('success', 'Ãƒâ€°vÃƒÂ©nement supprimÃƒÂ© avec succÃƒÂ¨s');
    }
}
