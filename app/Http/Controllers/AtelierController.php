<?php

namespace App\Http\Controllers;

use App\Models\Atelier;
use App\Models\Evenement;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAtelierRequest;
use App\Http\Requests\UpdateAtelierRequest;

class AtelierController extends Controller
{
    public function index(Evenement $evenement = null)
    {
        if ($evenement) {
            // Si un événement est fourni, vérifier les droits puis lister ses ateliers
            $user = Auth::user();
            if ($user->role !== 'super_admin') {
                $this->authorizeAccess($evenement);
            }

            $ateliers = $evenement->ateliers()->paginate(10);
            return view('ateliers.index', [
                'evenement' => $evenement,
                'ateliers' => $ateliers,
            ]);
        }

        // Show all ateliers when no evenement is provided
        $user = Auth::user();
        if ($user->role === 'super_admin') {
            $ateliers = Atelier::with('evenement')->paginate(10);
        } else {
            // Filtrer les ateliers pour ne montrer que ceux de l'entreprise de l'admin
            $collab = $user->collaborateurs()->first();
            if (!$collab || $collab->role !== 'admin_entreprise') {
                abort(403);
            }

            $entrepriseId = $collab->id_entreprise;
            $ateliers = Atelier::whereHas('evenement', function ($q) use ($entrepriseId) {
                $q->where('id_entreprise', $entrepriseId);
            })->with('evenement')->paginate(10);
        }
        return view('ateliers.index', [
            'ateliers' => $ateliers
        ]);
    }

    public function create(Evenement $evenement = null)
    {
        $user = Auth::user();
        // Super admin ne peut plus crÃ©er d'atelier
        if ($user->role === 'super_admin') {
            abort(403, 'Le super administrateur ne peut pas crÃ©er d\'atelier.');
        }
        $collab = $user->collaborateurs()->first();
        if (!$collab || $collab->role !== 'admin_entreprise') abort(403);
        // Admin entreprise only sees events of their entreprise
        $evenements = Evenement::where('id_entreprise', $collab->id_entreprise)->get();
        $selectedEvenement = $evenement;
        return view('ateliers.create', compact('evenements', 'selectedEvenement'));
    }

    public function store(StoreAtelierRequest $request)
    {
        $user = Auth::user();
        $evenement = Evenement::findOrFail($request->id_event);
        // Super admin ne peut plus crÃ©er d'atelier
        if ($user->role === 'super_admin') {
            abort(403, 'Le super administrateur ne peut pas crÃ©er d\'atelier.');
        }
        $collab = $user->collaborateurs()->first();
        if (!$collab || $collab->role !== 'admin_entreprise' || $evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403);
        }
        Atelier::create(array_merge($request->validated(), [
            'banniere' => $request->banniere ? $request->banniere->store('bannieres', 'public') : null,
        ]));
        return redirect()->route('ateliers.index')->with('success', 'Atelier ajoutÃ©');
    }

    public function edit(Evenement $evenement, Atelier $atelier)
    {
        $user = Auth::user();
        // Super admin ne peut plus modifier d'atelier
        if ($user->role === 'super_admin') {
            abort(403, 'Le super administrateur ne peut pas modifier d\'atelier.');
        }
        $this->authorizeAccess($evenement);
        return view('ateliers.edit', compact('evenement', 'atelier'));
    }

    public function update(UpdateAtelierRequest $request, Evenement $evenement, Atelier $atelier)
    {
        $user = Auth::user();
        // Super admin ne peut plus modifier d'atelier
        if ($user->role === 'super_admin') {
            abort(403, 'Le super administrateur ne peut pas modifier d\'atelier.');
        }
        $this->authorizeAccess($evenement);
        $atelier->update(array_merge($request->validated(), [
            'banniere' => $request->banniere ? $request->banniere->store('bannieres', 'public') : $atelier->banniere,
        ]));
        return redirect()->route('evenements.show', $evenement->id_event)->with('success', 'Atelier mis Ã  jour');
    }

    public function show(Evenement $evenement, Atelier $atelier)
    {
        return view('ateliers.show', compact('evenement', 'atelier'));
    }

    public function destroy(Evenement $evenement, Atelier $atelier)
    {
        $user = Auth::user();
        // Super admin ne peut plus supprimer d'atelier
        if ($user->role === 'super_admin') {
            abort(403, 'Le super administrateur ne peut pas supprimer d\'atelier.');
        }
        $this->authorizeAccess($evenement);
        $atelier->delete();
        return redirect()->route('evenements.show', $evenement->id_event)->with('success', 'Atelier supprimÃ©');
    }

    /**
     * Helper technique pour centraliser la vÃ©rification des droits.
     */
    private function authorizeAccess(Evenement $evenement)
    {
        $user = Auth::user();
        // Super admin ne peut plus accÃ©der Ã  la modification des ateliers
        if ($user->role === 'super_admin') {
            abort(403, 'Le super administrateur ne peut pas accÃ©der Ã  cette action.');
        }
        // Sinon, on vÃ©rifie si c'est l'admin de l'entreprise concernÃ©e
        $collab = $user->collaborateurs()->first();
        if (!$collab || $collab->role !== 'admin_entreprise' || $collab->id_entreprise !== $evenement->id_entreprise) {
            abort(403, "Vous n'avez pas les droits nÃ©cessaires.");
        }
    }
    /**
     * Affiche les ateliers d'un événement (page publique du landing)
     */
    public function publicList(Evenement $evenement)
    {
        // Charger les ateliers avec l'événement
        $evenement->load(['ateliers', 'entreprise']);

        return view('landing.ateliers', compact('evenement'));
    }}