<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partenaire;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PartenaireController extends Controller
{
    /**
     * Vérifie si l'utilisateur est super admin
     */
    private function isSuperAdmin()
    {
        return Auth::user()->role === 'super_admin';
    }

    /**
     * Récupère l'entreprise de l'admin connecté
     */
    private function getUserEntrepriseId()
    {
        if ($this->isSuperAdmin()) {
            return null;
        }
        $collab = Auth::user()->collaborateurs()->first();
        return $collab ? $collab->id_entreprise : null;
    }

    /**
     * Display a listing of partenaires.
     */
    public function index()
    {
        $partenaires = Partenaire::ordered()->get();
        return view('admin.partenaires.index', compact('partenaires'));
    }

    /**
     * Show the form for creating a new partenaire.
     */
    public function create()
    {
        $types = Partenaire::TYPES;
        return view('admin.partenaires.create', compact('types'));
    }

    /**
     * Store a newly created partenaire.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'site_web' => 'nullable|url|max:255',
            'type' => 'required|in:gold,silver,bronze,media,institutionnel,autre',
            'ordre' => 'nullable|integer|min:0',
            'logo' => 'nullable|image|max:2048',
            'contrat' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('partenaires/logos', 'public');
        }

        // Handle contrat upload
        if ($request->hasFile('contrat')) {
            $validated['contrat'] = $request->file('contrat')->store('partenaires/contrats', 'public');
        }

        $validated['actif'] = $request->boolean('actif', true);
        $validated['ordre'] = $validated['ordre'] ?? 0;

        Partenaire::create($validated);

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Partenaire créé avec succès.');
    }

    /**
     * Display the specified partenaire.
     */
    public function show(Partenaire $partenaire)
    {
        $partenaire->load('evenements');
        return view('admin.partenaires.show', compact('partenaire'));
    }

    /**
     * Show the form for editing the specified partenaire.
     */
    public function edit(Partenaire $partenaire)
    {
        $types = Partenaire::TYPES;
        return view('admin.partenaires.edit', compact('partenaire', 'types'));
    }

    /**
     * Update the specified partenaire.
     */
    public function update(Request $request, Partenaire $partenaire)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'site_web' => 'nullable|url|max:255',
            'type' => 'required|in:gold,silver,bronze,media,institutionnel,autre',
            'ordre' => 'nullable|integer|min:0',
            'logo' => 'nullable|image|max:2048',
            'contrat' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($partenaire->logo) {
                Storage::disk('public')->delete($partenaire->logo);
            }
            $validated['logo'] = $request->file('logo')->store('partenaires/logos', 'public');
        }

        // Handle contrat upload
        if ($request->hasFile('contrat')) {
            // Delete old contrat
            if ($partenaire->contrat) {
                Storage::disk('public')->delete($partenaire->contrat);
            }
            $validated['contrat'] = $request->file('contrat')->store('partenaires/contrats', 'public');
        }

        $validated['actif'] = $request->boolean('actif', true);
        $validated['ordre'] = $validated['ordre'] ?? 0;

        $partenaire->update($validated);

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Partenaire mis à jour avec succès.');
    }

    /**
     * Remove the specified partenaire.
     */
    public function destroy(Partenaire $partenaire)
    {
        // Delete files
        if ($partenaire->logo) {
            Storage::disk('public')->delete($partenaire->logo);
        }
        if ($partenaire->contrat) {
            Storage::disk('public')->delete($partenaire->contrat);
        }

        $partenaire->delete();

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Partenaire supprimé avec succès.');
    }

    /**
     * Attach partenaire to an event.
     */
    public function attachToEvent(Request $request, Evenement $evenement)
    {
        $validated = $request->validate([
            'id_partenaire' => 'required|exists:partenaires,id_partenaire',
            'contribution' => 'nullable|string',
            'montant' => 'nullable|numeric|min:0',
        ]);

        $evenement->partenaires()->attach($validated['id_partenaire'], [
            'contribution' => $validated['contribution'] ?? null,
            'montant' => $validated['montant'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Partenaire ajouté à l\'événement.');
    }

    /**
     * Detach partenaire from an event.
     */
    public function detachFromEvent(Evenement $evenement, Partenaire $partenaire)
    {
        $evenement->partenaires()->detach($partenaire->id_partenaire);
        return redirect()->back()->with('success', 'Partenaire retiré de l\'événement.');
    }
}
