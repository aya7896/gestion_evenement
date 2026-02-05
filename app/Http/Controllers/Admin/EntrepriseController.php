<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Http\Requests\StoreEntrepriseRequest;
use App\Http\Requests\UpdateEntrepriseRequest;
use Illuminate\Support\Facades\Auth;

class EntrepriseController extends Controller
{
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
     * Calcule la taille de l'entreprise basée sur le nombre d'employés
     */
    private function calculateTailleFromEffectif($effectif)
    {
        if ($effectif <= 10) {
            return 'micro';
        } elseif ($effectif <= 50) {
            return 'petite';
        } elseif ($effectif <= 200) {
            return 'moyenne';
        } else {
            return 'grande';
        }
    }

    public function index()
    {
        // Super Admin voit toutes les entreprises
        // Admin Entreprise voit uniquement son entreprise
        if ($this->isSuperAdmin()) {
            $entreprises = Entreprise::all();
        } else {
            $entrepriseId = $this->getUserEntrepriseId();
            $entreprises = Entreprise::where('id_entreprise', $entrepriseId)->get();
        }

        return view('admin.entreprises.index', compact('entreprises'));
    }

    public function create()
    {
        // Seul le Super Admin peut créer des entreprises
        if (!$this->isSuperAdmin()) {
            abort(403, 'Seul le Super Admin peut créer des entreprises');
        }

        return view('admin.entreprises.create');
    }

    public function store(StoreEntrepriseRequest $request)
    {
        // Seul le Super Admin peut créer des entreprises
        if (!$this->isSuperAdmin()) {
            abort(403, 'Seul le Super Admin peut créer des entreprises');
        }

        $data = $request->validated();
        $data['taille_entreprise'] = $this->calculateTailleFromEffectif($data['effectif']);
        $data['logo'] = $request->logo ? $request->logo->store('logos', 'public') : null;

        Entreprise::create($data);

        return redirect()->route('admin.entreprises.index')->with('success', 'Entreprise créée avec succès');
    }

    public function edit(Entreprise $entreprise)
    {
        // Le Super Admin ne peut pas modifier
        if ($this->isSuperAdmin()) {
            abort(403, 'Action non autorisée pour le Super Admin');
        }

        // Admin Entreprise ne peut modifier que son entreprise
        $entrepriseId = $this->getUserEntrepriseId();
        if ($entreprise->id_entreprise !== $entrepriseId) {
            abort(403, 'Vous ne pouvez modifier que votre entreprise');
        }

        return view('admin.entreprises.edit', compact('entreprise'));
    }

    public function update(UpdateEntrepriseRequest $request, Entreprise $entreprise)
    {
        // Le Super Admin ne peut pas modifier
        if ($this->isSuperAdmin()) {
            abort(403, 'Action non autorisée pour le Super Admin');
        }

        // Admin Entreprise ne peut modifier que son entreprise
        $entrepriseId = $this->getUserEntrepriseId();
        if ($entreprise->id_entreprise !== $entrepriseId) {
            abort(403, 'Vous ne pouvez modifier que votre entreprise');
        }

        $data = $request->validated();
        // Recalculer la taille seulement si effectif est fourni
        if (isset($data['effectif'])) {
            $data['taille_entreprise'] = $this->calculateTailleFromEffectif($data['effectif']);
        }
        $data['logo'] = $request->logo ? $request->logo->store('logos', 'public') : $entreprise->logo;

        $entreprise->update($data);

        return redirect()->route('admin.entreprises.index')->with('success', 'Entreprise mise à jour avec succès');
    }

    public function destroy(Entreprise $entreprise)
    {
        // Le Super Admin ne peut pas supprimer
        if ($this->isSuperAdmin()) {
            abort(403, 'Action non autorisée pour le Super Admin');
        }

        $entreprise->delete();
        return redirect()->route('admin.entreprises.index')->with('success', 'Entreprise supprimée avec succès');
    }
}