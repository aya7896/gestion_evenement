<?php

namespace App\Http\Controllers;

use App\Models\Atelier;
use App\Models\Evenement;
use App\Models\Speaker;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAtelierRequest;
use App\Http\Requests\UpdateAtelierRequest;

class AtelierController extends Controller
{
    public function index(Evenement $evenement = null)
    {
        if ($evenement) {
            // Si un Ã©vÃ©nement est fourni, vÃ©rifier les droits puis lister ses ateliers
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
        // Super admin ne peut plus crÃƒÂ©er d'atelier
        if ($user->role === 'super_admin') {
            abort(403, 'Le super administrateur ne peut pas crÃƒÂ©er d\'atelier.');
        }
        $collab = $user->collaborateurs()->first();
        if (!$collab || $collab->role !== 'admin_entreprise') abort(403);
        // Admin entreprise only sees events of their entreprise
        $evenements = Evenement::where('id_entreprise', $collab->id_entreprise)->get();
        $speakers = Speaker::where('actif', true)->orderBy('nom')->orderBy('prenom')->get();
        $selectedEvenement = $evenement;
        return view('ateliers.create', compact('evenements', 'selectedEvenement', 'speakers'));
    }

    public function store(StoreAtelierRequest $request)
    {
        $user = Auth::user();
        $evenement = Evenement::findOrFail($request->id_event);
        // Super admin ne peut plus crÃƒÂ©er d'atelier
        if ($user->role === 'super_admin') {
            abort(403, 'Le super administrateur ne peut pas crÃƒÂ©er d\'atelier.');
        }
        $collab = $user->collaborateurs()->first();
        if (!$collab || $collab->role !== 'admin_entreprise' || $evenement->id_entreprise !== $collab->id_entreprise) {
            abort(403);
        }
        $request->validate([
            'speakers' => 'nullable|array',
            'speakers.*' => 'integer|exists:speakers,id_speaker',
            'new_speaker.nom' => 'nullable|string|max:255|required_with:new_speaker.prenom,new_speaker.email,new_speaker.poste,new_speaker.company,new_speaker.bio',
            'new_speaker.prenom' => 'nullable|string|max:255|required_with:new_speaker.nom,new_speaker.email,new_speaker.poste,new_speaker.company,new_speaker.bio',
            'new_speaker.email' => 'nullable|email|max:255',
            'new_speaker.poste' => 'nullable|string|max:255',
            'new_speaker.company' => 'nullable|string|max:255',
            'new_speaker.bio' => 'nullable|string',
            'new_speaker.photo' => 'nullable|image|max:2048',
        ]);

        $atelier = Atelier::create(array_merge($request->validated(), [
            'banniere' => $request->banniere ? $request->banniere->store('bannieres', 'public') : null,
        ]));

        $speakerIds = collect($request->input('speakers', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $newSpeakerData = (array) $request->input('new_speaker', []);
        $hasNewSpeaker = !empty(trim((string) ($newSpeakerData['nom'] ?? '')))
            && !empty(trim((string) ($newSpeakerData['prenom'] ?? '')));

        if ($hasNewSpeaker) {
            $photoPath = $request->hasFile('new_speaker.photo')
                ? $request->file('new_speaker.photo')->store('speakers/photos', 'public')
                : null;

            $newSpeaker = Speaker::create([
                'nom' => trim((string) ($newSpeakerData['nom'] ?? '')),
                'prenom' => trim((string) ($newSpeakerData['prenom'] ?? '')),
                'email' => !empty($newSpeakerData['email']) ? trim((string) $newSpeakerData['email']) : null,
                'poste' => !empty($newSpeakerData['poste']) ? trim((string) $newSpeakerData['poste']) : null,
                'company' => !empty($newSpeakerData['company']) ? trim((string) $newSpeakerData['company']) : null,
                'bio' => !empty($newSpeakerData['bio']) ? trim((string) $newSpeakerData['bio']) : null,
                'photo' => $photoPath,
                'actif' => true,
            ]);

            $speakerIds[] = (int) $newSpeaker->id_speaker;
            $speakerIds = array_values(array_unique($speakerIds));
        }
        if (!empty($speakerIds)) {
            $syncData = collect($speakerIds)->mapWithKeys(
                fn ($id) => [(int) $id => ['role' => 'speaker', 'ordre' => 0]]
            )->all();
            $atelier->speakers()->sync($syncData);
        }
    
        // Redirection pour l'admin d'entreprise
        if ($user->role === 'admin_entreprise') {
            return redirect()->route('admin.evenements.show', $evenement->id_event)->with('success', 'Atelier ajouté');
        }
    
        return redirect()->route('ateliers.index')->with('success', 'Atelier ajouté');
    }

    public function edit(Evenement $evenement, Atelier $atelier)
    {
        $user = Auth::user();
        // Super admin ne peut plus modifier d'atelier
        if ($user->role === 'super_admin') {
            abort(403, 'Le super administrateur ne peut pas modifier d\'atelier.');
        }
        $this->authorizeAccess($evenement);
        $atelier->load('speakers');
        $speakers = Speaker::where('actif', true)->orderBy('nom')->orderBy('prenom')->get();
        return view('ateliers.edit', compact('evenement', 'atelier', 'speakers'));
    }

    public function update(UpdateAtelierRequest $request, Evenement $evenement, Atelier $atelier)
    {
        $user = Auth::user();
        // Super admin ne peut plus modifier d'atelier
        if ($user->role === 'super_admin') {
            abort(403, 'Le super administrateur ne peut pas modifier d\'atelier.');
        }
        $this->authorizeAccess($evenement);
        $request->validate([
            'speakers' => 'nullable|array',
            'speakers.*' => 'integer|exists:speakers,id_speaker',
        ]);
        $atelier->update(array_merge($request->validated(), [
            'banniere' => $request->banniere ? $request->banniere->store('bannieres', 'public') : $atelier->banniere,
        ]));
        $speakerIds = $request->input('speakers', []);
        $syncData = collect($speakerIds)->mapWithKeys(
            fn ($id) => [(int) $id => ['role' => 'speaker', 'ordre' => 0]]
        )->all();
        $atelier->speakers()->sync($syncData);

        if ($user->role === 'admin_entreprise') {
            return redirect()->route('admin.evenements.show', $evenement->id_event)->with('success', 'Atelier mis à jour');
        }
    
        return redirect()->route('evenements.show', $evenement->id_event)->with('success', 'Atelier mis à jour');
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
        return redirect()->route('evenements.show', $evenement->id_event)->with('success', 'Atelier supprimÃƒÂ©');
    }

    /**
     * Helper technique pour centraliser la vÃƒÂ©rification des droits.
     */
    private function authorizeAccess(Evenement $evenement)
    {
        $user = Auth::user();
        // Super admin ne peut plus accÃƒÂ©der Ãƒ  la modification des ateliers
        if ($user->role === 'super_admin') {
            abort(403, 'Le super administrateur ne peut pas accÃƒÂ©der Ãƒ  cette action.');
        }
        // Sinon, on vÃƒÂ©rifie si c'est l'admin de l'entreprise concernÃƒÂ©e
        $collab = $user->collaborateurs()->first();
        if (!$collab || $collab->role !== 'admin_entreprise' || $collab->id_entreprise !== $evenement->id_entreprise) {
            abort(403, "Vous n'avez pas les droits nÃƒÂ©cessaires.");
        }
    }
    /**
     * Affiche les ateliers d'un Ã©vÃ©nement (page publique du landing)
     */
    public function publicList(Evenement $evenement)
    {
        // Charger les ateliers avec l'Ã©vÃ©nement
        $evenement->load(['ateliers.speakers', 'entreprise']);

        return view('landing.ateliers', compact('evenement'));
    }}