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

            DB::commit();

            // Envoyer l'email de confirmation
            // Mail::to($user->email)->send(new InscriptionConfirmation($inscription));

            // Rediriger vers une page de confirmation
            return redirect()->route('public.evenement.landing', $evenement)
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
        $inscription->load('evenement');
        return view('inscriptions.confirmation', compact('inscription'));
    }

    /**
     * Génère et télécharge le badge du participant en PDF.
     */
    public function downloadBadge(Inscription $inscription)
    {
        // S'assurer que les données nécessaires sont chargées
        $inscription->load(['evenement', 'evenement.entreprise']);

        // Générer le QR code
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(200)
            ->errorCorrection('H')
            ->generate($inscription->qr_code_data));

        // Charger la vue du badge avec les données
        $pdf = Pdf::loadView('pdf.badge', [
            'inscription' => $inscription,
            'qrCode' => $qrCode
        ]);

        // Rendre le PDF téléchargeable
        $filename = 'badge-' . Str::slug($inscription->nom . '-' . $inscription->prenom) . '.pdf';
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