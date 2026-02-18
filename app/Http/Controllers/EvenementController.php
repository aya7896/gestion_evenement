<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Collaborateur;
use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('landing.index', compact('evenement'));
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
        return view('evenements.create', compact('entreprises', 'partenaires'));
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
        ]);
        $plaquettePath = $request->plaquette_pdf ? $request->plaquette_pdf->store('plaquettes') : null;
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
        $evenement = Evenement::create(array_merge($request->validated(), [
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

    public function detachPartenaire(Evenement $evenement, Partenaire $partenaire)
    {
        if ($this->isSuperAdmin()) {
            abort(403, 'Le super administrateur ne peut pas modifier les partenaires.');
        }
        $collab = $this->getUserCollaborateur();
        if (!$collab || $collab->role !== 'admin_entreprise' || $evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403, 'Vous ne pouvez modifier que les partenaires de vos Ã©vÃ©nements.');
        }

        $evenement->partenaires()->detach($partenaire->id_partenaire);
        return redirect()->route('evenements.show', $evenement)->with('success', 'Sponsor retirÃ© de l\'Ã©vÃ©nement.');
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
        return view('evenements.edit', compact('evenement', 'partenaires'));
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
        $evenement->update(array_merge($request->validated(), [
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