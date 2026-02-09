<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\User;
use App\Models\Inscription;
use App\Models\Inscription_event;
use App\Mail\InscriptionConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class InscriptionController extends Controller
{
    /**
     * Affiche le formulaire d'inscription public pour un événement.
     */
    public function create(Evenement $evenement)
    {
        // Charger les ateliers pour les afficher dans le formulaire
        $evenement->load('ateliers');
        return view('landing.inscription', compact('evenement'));
    }

    /**
     * Enregistre une nouvelle inscription depuis le formulaire public.
     */
    public function store(Request $request, Evenement $evenement)
    {
        // Validation de base (toujours requis)
        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'company' => 'nullable|string|max:255',
            'one_to_one' => 'nullable|boolean',
        ];

        // Si one-to-one est coché, ajouter les validations supplémentaires
        if ($request->has('one_to_one') && $request->one_to_one) {
            $rules['photo'] = 'required|image|mimes:jpeg,png,jpg,gif|max:4096';
            $rules['presentation'] = 'required|string|max:1000';
            $rules['poste'] = 'required|string|max:255';
            $rules['lien_linkedin'] = 'required|string|max:255';
            $rules['objectif'] = 'required|string|max:1000';
        }

        $data = $request->validate($rules);

        try {
            DB::beginTransaction();

            // 1. Créer ou récupérer l'utilisateur (participant)
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'nom' => $data['nom'],
                    'prenom' => $data['prenom'],
                    'telephone' => $data['telephone'],
                    'password' => Hash::make($data['password']),
                    'role' => 'participant',
                ]
            );

            $inscriptionId = null;

            // 2. Si one-to-one est coché, créer une inscription complète
            if ($request->has('one_to_one') && $request->one_to_one) {
                
                // Gérer l'upload de la photo
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store('photos', 'public');
                }

                // Créer l'inscription avec les informations complémentaires
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
                // 3. Si one-to-one n'est pas coché, créer une inscription basique (minimale)
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
            ]);

            // 5. Ajouter les ateliers sélectionnés
            if ($request->has('ateliers') && is_array($request->ateliers)) {
                $inscription->ateliers()->attach($request->ateliers);
            }

            DB::commit();

            // Envoyer l'email de confirmation
            // Mail::to($user->email)->send(new InscriptionConfirmation($inscription));

            // Stocker l'ID d'inscription et l'email dans la session
            session([
                'inscription_id' => $inscription->id_inscription,
                'inscription_email' => $user->email,
            ]);

            // Rediriger vers la page de confirmation
            return redirect()->route('inscription.confirmation', $inscription)
                ->with('success', 'Votre inscription a été confirmée !');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur détaillée d\'inscription: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Une erreur est survenue lors de votre inscription. Veuillez réessayer.');
        }
    }

    /**
     * Affiche la page de confirmation après une inscription réussie.
     */
    public function confirmation(Inscription $inscription)
    {
        $inscription->load('evenements');
        $evenement = $inscription->evenements->first();
        return view('inscriptions.confirmation', compact('inscription', 'evenement'));
    }

    /**
     * Affiche les détails complets d'une inscription.
     */
    public function show(Inscription $inscription)
    {
        $inscription->load(['user', 'evenements', 'ateliers']);
        return view('inscriptions.show', compact('inscription'));
    }

    /**
     * Génère et télécharge le badge du participant en PDF.
     */
    public function downloadBadge(Inscription $inscription)
    {
        // Charger les relations nécessaires
        $inscription->load(['user', 'evenements', 'ateliers', 'evenements.entreprise']);

        // Préparer le contenu du QR : utiliser une donnée existante ou un lien public de confirmation
        $qrData = $inscription->qr_code_data ?? route('inscription.confirmation', $inscription->id_inscription);

        // Générer le QR code en SVG (encodé en base64) pour éviter la dépendance Imagick
        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($qrData);

        $qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        // Charger la vue du badge A6
        $pdf = Pdf::loadView('pdf.badge', [
            'inscription' => $inscription,
            'qrCode' => $qrCode
        ]);

        // Nom de fichier basé sur le nom de l'utilisateur
        $filename = 'badge-' . Str::slug($inscription->user->name ?? ('inscription-' . $inscription->id_inscription)) . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Permet de télécharger la plaquette de l'événement via la page de confirmation.
     */
    public function downloadPlaquette(Inscription $inscription)
    {
        $evenement = $inscription->evenement;

        // On appelle la méthode déjà existante sur EvenementController
        $eventController = new EvenementController();
        return $eventController->downloadPlaquette($evenement);
    }

    /**
     * Valide une ou plusieurs inscriptions (réservé aux admin_entreprise)
     */
    public function valider(Request $request)
    {
        $user = auth()->user();
        
        // Vérifier que l'utilisateur est un collaborateur avec le rôle admin_entreprise
        $collab = $user->collaborateurs()->first();
        if (!$collab || $collab->role !== 'admin_entreprise') {
            return back()->with('error', 'Vous n\'avez pas la permission de valider les inscriptions.');
        }

        // Valider la requête
        $request->validate([
            'inscription_ids' => 'nullable|array',
            'inscription_ids.*' => 'integer|exists:inscriptions,id_inscription',
            'evenement_id' => 'required|integer|exists:evenements,id_event',
        ]);

        $evenementId = $request->input('evenement_id');

        // Vérifier que l'utilisateur a accès à cet événement
        $evenement = Evenement::findOrFail($evenementId);
        if ($evenement->id_entreprise !== $collab->id_entreprise) {
            return back()->with('error', 'Vous n\'avez pas accès à cet événement.');
        }

        try {
            DB::beginTransaction();

            $action = $request->input('action', 'selected');
            
            if ($action === 'all') {
                // Valider toutes les inscriptions non validées
                $count = Inscription::whereIn('id_inscription', 
                    $evenement->inscriptions->pluck('id_inscription')->toArray()
                )
                ->where('statut', '!=', 'validée')
                ->update(['statut' => 'validée']);
            } else {
                // Valider les inscriptions sélectionnées
                $inscriptionIds = $request->input('inscription_ids', []);
                
                if (empty($inscriptionIds)) {
                    return back()->with('error', 'Veuillez sélectionner au moins une inscription.');
                }

                $count = Inscription::whereIn('id_inscription', $inscriptionIds)
                    ->where('statut', '!=', 'validée')
                    ->update(['statut' => 'validée']);
            }

            DB::commit();

            if ($count === 0) {
                return back()->with('info', 'Aucune inscription à valider (toutes sont déjà validées).');
            }

            return back()->with('success', ($count === 1) 
                ? 'L\'inscription a été validée avec succès.' 
                : $count . ' inscriptions ont été validées avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la validation des inscriptions: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la validation.');
        }
    }

    /**
     * Exporte les inscriptions d'un événement en CSV
     */
    public function exportCsv($evenementId)
    {
        // Vérifier l'authentification
        if (!auth()->check()) {
            return back()->with('error', 'Vous devez être connecté.');
        }

        $user = auth()->user();
        $evenement = Evenement::findOrFail($evenementId);

        // Vérifier que l'utilisateur a accès à cet événement
        if ($user->role !== 'super_admin') {
            $collab = $user->collaborateurs()->first();
            if (!$collab || $evenement->id_entreprise !== $collab->id_entreprise) {
                return back()->with('error', 'Vous n\'avez pas accès à cet événement.');
            }
        }

        // Récupérer les inscriptions
        $inscriptions = $evenement->inscriptions()->with('user')->get();

        // Créer le fichier CSV
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
            
            // En-têtes du CSV
            fputcsv($file, [
                'Participant',
                'Prénom',
                'Nom',
                'Email',
                'Entreprise',
                'Poste',
                'LinkedIn',
                'Présentation',
                'Objectif',
                'Date d\'inscription',
                'Statut'
            ], ';');

            // Données des inscriptions
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
        $inscription->statut = 'annulée';
        $inscription->save();

        // Rediriger vers une page informant que l'annulation est réussie
        return view('inscriptions.cancelled', [
            'evenement' => $inscription->evenement
        ]);
    }
}