<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collaborateur;
use App\Models\User;
use App\Models\Entreprise;
use App\Http\Requests\StoreCollaborateurRequest;
use App\Http\Requests\UpdateCollaborateurRequest;
use Illuminate\Support\Facades\Auth;

class CollaborateurController extends Controller
{
    /**
     * Affiche les détails d'un collaborateur
     */
    public function show(Collaborateur $collaborateur)
    {
        // Admin Entreprise ne peut consulter que les collaborateurs de son entreprise
        if (!$this->isSuperAdmin()) {
            $entrepriseId = $this->getUserEntrepriseId();
            if ($collaborateur->id_entreprise !== $entrepriseId) {
                abort(403, 'Vous ne pouvez consulter que les collaborateurs de votre entreprise');
            }
        }
        return view('admin.collaborateurs.show', compact('collaborateur'));
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

    public function index()
    {
        // Super Admin voit tous les collaborateurs
        // Admin Entreprise voit uniquement les collaborateurs de son entreprise, y compris lui-même si admin_entreprise
        if ($this->isSuperAdmin()) {
            $collaborateurs = Collaborateur::with(['user', 'entreprise'])->get();
        } else {
            $entrepriseId = $this->getUserEntrepriseId();
            if ($entrepriseId) {
                $collaborateurs = Collaborateur::where('id_entreprise', $entrepriseId)
                    ->where('active', true)
                    ->with(['user', 'entreprise'])
                    ->get();
                // Inclure aussi le collaborateur admin_entreprise connecté s'il n'est pas déjà dans la liste
                $self = Collaborateur::where('id_user', Auth::id())
                    ->where('role', 'admin_entreprise')
                    ->where('active', true)
                    ->with(['user', 'entreprise'])
                    ->first();
                if ($self && !$collaborateurs->contains('id_Collaborateur', $self->id_Collaborateur)) {
                    $collaborateurs->push($self);
                }
            } else {
                $collaborateurs = collect();
            }
        }

        return view('admin.collaborateurs.index', compact('collaborateurs'));
    }

    public function create()
    {
        // Super Admin peut choisir l'entreprise
        // Admin Entreprise et Admin peuvent voir toutes les entreprises mais ne peuvent affecter que dans la sienne
        if ($this->isSuperAdmin()) {
            $entreprises = Entreprise::all();
        } else {
            // Admin Entreprise et Admin voient toutes les entreprises pour comprendre le système
            // mais ne peuvent sélectionner que la sienne (géré côté frontend)
            $entreprises = Entreprise::all();
        }

        return view('admin.collaborateurs.create', compact('entreprises'));
    }

    public function store(StoreCollaborateurRequest $request)
    {
        $data = $request->validated();

        // Si Admin Entreprise, forcer l'entreprise si possible, sinon utiliser l'ID fourni dans le formulaire
        if (!$this->isSuperAdmin()) {
            $entrepriseId = $this->getUserEntrepriseId();

            if ($entrepriseId) {
                $data['id_entreprise'] = $entrepriseId;
            } elseif ($request->filled('id_entreprise')) {
                // Allow admin without collaborator profile to specify entreprise in the form
                $data['id_entreprise'] = $request->input('id_entreprise');
            } else {
                abort(403, 'Aucune entreprise associée');
            }
        }

        // Créer l'utilisateur
        $user = User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'collaborateur', // Tous les collaborateurs sont des collaborateurs
        ]);

        // Créer le collaborateur
        Collaborateur::create([
            'id_user' => $user->id_user,
            'id_entreprise' => $data['id_entreprise'],
            'role' => $data['role'],
            'active' => $data['active'],
        ]);

        return redirect()->route('admin.collaborateurs.index')->with('success', 'Collaborateur et utilisateur créés avec succès');
    }

    public function edit(Collaborateur $collaborateur)
    {
        // Admin Entreprise ne peut modifier que les collaborateurs de son entreprise
        if (!$this->isSuperAdmin()) {
            $entrepriseId = $this->getUserEntrepriseId();
            if ($collaborateur->id_entreprise !== $entrepriseId) {
                abort(403, 'Vous ne pouvez modifier que les collaborateurs de votre entreprise');
            }
            // Limiter la liste aux entreprises de l'admin (une seule)
            $entreprises = Entreprise::where('id_entreprise', $entrepriseId)->get();
        } else {
            // Super admin voit toutes les entreprises pour pouvoir réassigner
            $entreprises = Entreprise::all();
        }

        return view('admin.collaborateurs.edit', compact('collaborateur', 'entreprises'));
    }

    public function update(UpdateCollaborateurRequest $request, Collaborateur $collaborateur)
    {
        // Admin Entreprise ne peut modifier que les collaborateurs de son entreprise
        if (!$this->isSuperAdmin()) {
            $entrepriseId = $this->getUserEntrepriseId();
            if ($collaborateur->id_entreprise !== $entrepriseId) {
                abort(403, 'Vous ne pouvez modifier que les collaborateurs de votre entreprise');
            }
        }

        $collaborateur->update($request->validated());

        return redirect()->route('admin.collaborateurs.index')->with('success', 'Collaborateur mis à jour avec succès');
    }

    public function destroy(Collaborateur $collaborateur)
    {
        // Admin Entreprise ne peut supprimer que les collaborateurs de son entreprise
        if (!$this->isSuperAdmin()) {
            $entrepriseId = $this->getUserEntrepriseId();
            if ($collaborateur->id_entreprise !== $entrepriseId) {
                abort(403, 'Vous ne pouvez supprimer que les collaborateurs de votre entreprise');
            }
        }

        $collaborateur->delete();
        return redirect()->route('admin.collaborateurs.index')->with('success', 'Collaborateur supprimé avec succès');
    }
}
