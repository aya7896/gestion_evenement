<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\Entreprise;
use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvenementController extends Controller
{
    /**
     * Affiche le formulaire de creation d'un evenement.
     */
    public function create()
    {
        $entreprises = null;
        $partenaires = Partenaire::actif()->ordered()->get();

        if (auth()->user()->role === 'super_admin') {
            $entreprises = Entreprise::orderBy('nom')->get();
        }

        return view('admin.evenements.create', compact('entreprises', 'partenaires'));
    }

    /**
     * Enregistre un nouvel evenement.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'capacite' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'type' => 'required|in:conference,workshop,seminaire,formation,autre,conférence,séminaire',
            'localisation' => 'nullable|string|max:255',
            'lieu' => 'nullable|string|max:255',
            'date_heure_debut' => 'required|date',
            'date_heure_fin' => 'required|date|after:date_heure_debut',
            'mode' => 'required|in:presentiel,en ligne,hybride,présentiel',
            'color_template' => 'nullable|in:violet,ocean,sunset,forest,slate',
            'hero_appearance' => 'nullable|in:glass_soft,glass_strong,clean,cinematic',
            'id_entreprise' => 'nullable|exists:entreprises,id_entreprise',
            'event_link' => 'nullable|url',
            'visibility' => 'nullable|in:public,private',
            'status' => 'nullable|in:active,inactive',
            'plaquette_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'image' => 'nullable|image|max:2048',
            'partenaires' => 'nullable|array',
            'partenaires.*' => 'integer|exists:partenaires,id_partenaire',
        ]);

        $plaquettePath = $request->hasFile('plaquette_pdf')
            ? $request->file('plaquette_pdf')->store('plaquettes', 'public')
            : null;
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('images', 'public')
            : null;

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

        $evenement = Evenement::create($data);

        $partenaireIds = collect($request->input('partenaires', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
        $evenement->partenaires()->sync($partenaireIds);

        return redirect()->route('admin.evenements.index')->with('success', 'Evenement cree avec succes');
    }

    /**
     * Display events grouped by company for superadmin.
     */
    public function indexByCompany()
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Acces reserve au super administrateur');
        }

        $entreprises = Entreprise::with(['evenements.ateliers'])
            ->orderBy('nom')
            ->get();

        $groupedData = $entreprises->map(function ($entreprise) {
            return [
                'entreprise' => $entreprise,
                'total_evenements' => $entreprise->evenements->count(),
                'total_ateliers' => $entreprise->evenements->sum(fn ($e) => $e->ateliers->count()),
                'total_participants' => $entreprise->evenements->sum('capacite'),
                'evenements' => $entreprise->evenements->sortByDesc('created_at'),
            ];
        });

        return view('admin.evenements.by-company', compact('groupedData'));
    }

    /**
     * Affiche les evenements pour l'admin entreprise.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.evenements.by-company');
        }

        $collaborateur = $user->collaborateurs()->first();

        if (!$collaborateur || !$collaborateur->entreprise) {
            $groupedData = collect();
            return view('admin.evenements.by-company', compact('groupedData'));
        }

        $evenements = Evenement::with('ateliers')
            ->where('id_entreprise', $collaborateur->id_entreprise)
            ->orderByDesc('created_at')
            ->get();

        $groupedData = collect([
            [
                'entreprise' => $collaborateur->entreprise,
                'total_evenements' => $evenements->count(),
                'total_ateliers' => $evenements->sum(fn ($e) => $e->ateliers->count()),
                'total_participants' => $evenements->sum('capacite'),
                'evenements' => $evenements,
            ],
        ]);

        return view('admin.evenements.by-company', compact('groupedData'));
    }
}
