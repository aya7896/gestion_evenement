<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\Entreprise;
use Illuminate\Support\Facades\Auth;

class EvenementController extends Controller
{
    /**
     * Affiche le formulaire de création d'un événement (admin_entreprise et super_admin)
     */
    public function create()
    {
        // Ici, on peut ajouter des données nécessaires au formulaire si besoin (ex: entreprises, types, etc.)
        $entreprises = null;
        if (auth()->user()->role === 'super_admin') {
            $entreprises = Entreprise::orderBy('nom')->get();
        }
        return view('admin.evenements.create', compact('entreprises'));
    }

    /**
     * Enregistre un nouvel événement (admin_entreprise et super_admin)
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'capacite' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'type' => 'required|in:conférence,workshop,séminaire,formation,autre',
            'localisation' => 'nullable|string|max:255',
            'lieu' => 'nullable|string|max:255',
            'date_heure_debut' => 'required|date',
            'date_heure_fin' => 'required|date|after:date_heure_debut',
            'mode' => 'required|in:présentiel,en ligne,hybride',
            'id_entreprise' => 'nullable|exists:entreprises,id_entreprise',
            'event_link' => 'nullable|url',
            'visibility' => 'nullable|in:public,private',
            'status' => 'nullable|in:active,inactive',
            'validation_superAdmin' => 'nullable|in:0,1',
            'plaquette_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'image' => 'nullable|image|max:2048',
        ]);

        // Fichiers
        $plaquettePath = $request->hasFile('plaquette_pdf') ? $request->file('plaquette_pdf')->store('plaquettes', 'public') : null;
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;

        // Attribution entreprise/collaborateur
        if ($user->role === 'super_admin') {
            $data['id_entreprise'] = $request->input('id_entreprise');
            $data['id_Collaborateur'] = null;
        } else {
            $collab = $user->collaborateurs()->first();
            $data['id_entreprise'] = $collab ? $collab->id_entreprise : null;
            $data['id_Collaborateur'] = $collab ? $collab->id_Collaborateur : null;
        }
        $data['plaquette_pdf'] = $plaquettePath;
        $data['image'] = $imagePath;

        Evenement::create($data);

        return redirect()->route('admin.evenements.index')->with('success', "Événement créé avec succès");
    }
    /**
     * Display events grouped by company for superadmin
     */
    public function indexByCompany()
    {
        // Ensure only superadmin can access this
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Accès réservé au super administrateur');
        }

        // Get all companies with their events, including ateliers
        $entreprises = Entreprise::with(['evenements.ateliers'])
            ->orderBy('nom')
            ->get();

        // Group events by company status for better organization
        $groupedData = $entreprises->map(function ($entreprise) {
            return [
                'entreprise' => $entreprise,
                'total_evenements' => $entreprise->evenements->count(),
                'total_ateliers' => $entreprise->evenements->sum(fn($e) => $e->ateliers->count()),
                'total_participants' => $entreprise->evenements->sum('capacite'),
                'evenements' => $entreprise->evenements->sortByDesc('created_at')
            ];
        });

        return view('admin.evenements.by-company', compact('groupedData'));
    }
    /**
     * Affiche les événements pour l'admin_entreprise (uniquement ceux de son entreprise)
     */
    public function index()
    {
        $user = Auth::user();

        // Pour le super_admin, on redirige comme avant.
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.evenements.by-company');
        }

        // On récupère le profil collaborateur de l'utilisateur connecté.
        $collaborateur = $user->collaborateurs()->first();

        // Si l'utilisateur n'a pas de profil collaborateur ou d'entreprise associée, on ne peut rien afficher.
        if (!$collaborateur || !$collaborateur->entreprise) {
            $groupedData = collect();
            return view('admin.evenements.by-company', compact('groupedData'));
        }

        // On récupère uniquement les événements créés par ce collaborateur.
        $evenements = Evenement::with('ateliers')
            ->where('id_Collaborateur', $collaborateur->id_Collaborateur)
            ->orderByDesc('created_at')
            ->get();

        // On prépare la structure de données attendue par la vue.
        $groupedData = collect([
            [
                'entreprise' => $collaborateur->entreprise,
                'total_evenements' => $evenements->count(),
                'total_ateliers' => $evenements->sum(fn($e) => $e->ateliers->count()),
                'total_participants' => $evenements->sum('capacite'),
                'evenements' => $evenements
            ]
        ]);

        // On retourne la vue avec les données des événements de ce collaborateur.
        return view('admin.evenements.by-company', compact('groupedData'));
    }
}