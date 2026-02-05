<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Collaborateur;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreEvenementRequest;
use App\Http\Requests\UpdateEvenementRequest;

class EvenementController extends Controller
{
    /**
     * Gère l'inscription publique à un événement (nom, email)
     */
    public function publicInscription($id)
    {
        $evenement = Evenement::where('id_event', $id)->firstOrFail();
        request()->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
        // Ici, on peut enregistrer l'inscription dans une table dédiée ou envoyer un email
        // Pour la démo, on stocke dans la session
        session()->flash('success', "Inscription enregistrée ! Merci pour votre participation.");
        return redirect()->route('public.evenement.landing', $evenement->id_event);
    }

    /**
     * Affiche la landing page publique d'un événement (partage)
     */
    public function publicLanding($id)
    {
        $evenement = Evenement::with(['ateliers', 'entreprise'])->where('id_event', $id)->firstOrFail();
        return view('landing.index', compact('evenement'));
    }
    /**
     * Vérifie si l'utilisateur est super admin
     */
    private function isSuperAdmin()
    {
        return Auth::user()->role === 'super_admin';
    }

    /**
     * Récupère l'entreprise de l'admin connecté (si admin_entreprise)
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
     * Récupère le collaborateur connecté
     */
    private function getUserCollaborateur()
    {
        return Auth::user()->collaborateurs()->first();
    }

    public function index()
    {
        // Super Admin voit tous les événements
        // Admin Entreprise voit uniquement les événements de son entreprise
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
        // Super Admin ne peut plus créer d'événements
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas créer d\'événement.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise') {
            abort(403, 'Seuls les administrateurs peuvent créer des événements');
        }
        // admin_entreprise crée pour sa propre entreprise (pas d'option à choisir)
        $entreprises = null;
        return view('evenements.create', compact('entreprises'));
    }

    public function store(StoreEvenementRequest $request)
    {
        // Super Admin ne peut plus créer d'événements
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas créer d\'événement.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise') {
            abort(403, 'Seuls les administrateurs peuvent créer des événements');
        }
        $plaquettePath = $request->plaquette_pdf ? $request->plaquette_pdf->store('plaquettes') : null;
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
        Evenement::create(array_merge($request->validated(), [
            'id_Collaborateur' => $collab->id_Collaborateur,
            'id_entreprise' => $collab->id_entreprise,
            'plaquette_pdf' => $plaquettePath,
            'image' => $imagePath,
        ]));
        return redirect()->route('evenements.index')->with('success', 'Événement créé avec succès');
    }

    public function show(Evenement $evenement)
    {
        // Vérifier l'accèss pour Admin Entreprise
        if (!$this->isSuperAdmin()) {
            $entrepriseId = $this->getUserEntrepriseId();
            if ($evenement->id_entreprise !== $entrepriseId) {
                abort(403, 'Vous ne pouvez voir que les événements de votre entreprise');
            }
        }

        // Load related data used by the view
        $evenement->load(['ateliers', 'entreprise', 'inscriptions.user']);

        // Normalize image if it's an absolute path (e.g., leftover temp path) - copy to public storage
        if ($evenement->image && preg_match('/^[A-Z]:\\\\/i', $evenement->image) && file_exists($evenement->image)) {
            try {
                $newPath = 'images/' . uniqid() . '_' . basename($evenement->image);
                \Storage::disk('public')->put($newPath, file_get_contents($evenement->image));
                $evenement->image = $newPath;
                $evenement->save();
            } catch (\Throwable $e) {
                // fail silently; view will still try to display what it can
            }
        }
        // If the image still doesn't exist on either public disk or as absolute file, clear it to avoid broken images
        if ($evenement->image && ! \Storage::disk('public')->exists($evenement->image) && ! file_exists($evenement->image)) {
            $evenement->image = null;
            $evenement->save();
        }
        return view('evenements.show', compact('evenement'));
    }

    /**
     * Download existing plaquette PDF or generate one on-the-fly including event image.
     */
    public function downloadPlaquette(Evenement $evenement)
    {
        // Vérifier l'accèss pour Admin Entreprise
        if (!$this->isSuperAdmin()) {
            $entrepriseId = $this->getUserEntrepriseId();
            if ($evenement->id_entreprise !== $entrepriseId) {
                abort(403, 'Vous ne pouvez télécharger que les plaquettes de votre entreprise');
            }
        }

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
        abort(404, "Plaquette non trouvée et la génération PDF n'est pas disponible. Installez 'barryvdh/laravel-dompdf' pour activer la génération de PDF.");
    }

    public function edit(Evenement $evenement)
    {
        // Super Admin ne peut plus modifier d'événements
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas modifier d\'événement.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise') {
            abort(403, 'Seuls les administrateurs peuvent modifier des événements');
        }
        // Vérifier que c'est son entreprise
        if ($evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403, 'Vous ne pouvez modifier que les événements de votre entreprise');
        }
        return view('evenements.edit', compact('evenement'));
    }

    public function update(UpdateEvenementRequest $request, Evenement $evenement)
    {
        // Super Admin ne peut plus modifier d'événements
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas modifier d\'événement.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise') {
            abort(403, 'Seuls les administrateurs peuvent modifier des événements');
        }
        // Vérifier que c'est son entreprise
        if ($evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403, 'Vous ne pouvez modifier que les événements de votre entreprise');
        }
        $plaquettePath = $request->plaquette_pdf ? $request->plaquette_pdf->store('plaquettes') : $evenement->plaquette_pdf;
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : $evenement->image;
        $evenement->update(array_merge($request->validated(), [
            'plaquette_pdf' => $plaquettePath,
            'image' => $imagePath,
        ]));
        return redirect()->route('evenements.index')->with('success', 'Événement mis à jour avec succès');
    }

    public function destroy(Evenement $evenement)
    {
        // Super Admin ne peut plus supprimer d'événements
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas supprimer d\'événement.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise') {
            abort(403, 'Seuls les administrateurs peuvent supprimer des événements');
        }
        // Vérifier que c'est son entreprise
        if ($evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403, 'Vous ne pouvez supprimer que les événements de votre entreprise');
        }
        $evenement->delete();
        return redirect()->route('evenements.index')->with('success', 'Événement supprimé avec succès');
    }
}