<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\Entreprise;
use App\Models\Partenaire;
use App\Models\Atelier;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvenementController extends Controller
{
    /**
     * Affiche le formulaire de creation d'un evenement.
     */
    public function create()
    {
        $entreprises = null;
        $partenaires = Partenaire::actif()->ordered()->get();
        $speakers = Speaker::where('actif', true)->orderBy('nom')->orderBy('prenom')->get();
        $partenaireTypes = Partenaire::TYPES;

        if (auth()->user()->role === 'super_admin') {
            $entreprises = Entreprise::orderBy('nom')->get();
        }

        $landingTemplates = Evenement::LANDING_TEMPLATES;

        return view('admin.evenements.create', compact('entreprises', 'partenaires', 'speakers', 'partenaireTypes', 'landingTemplates'));
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
            'landing_template' => 'nullable|in:template_1,template_2,template_3,template_4',
            'landing_content' => 'nullable|array',
            'landing_content.hero_title' => 'nullable|string|max:255',
            'landing_content.hero_subtitle' => 'nullable|string|max:1000',
            'landing_content.primary_cta_text' => 'nullable|string|max:120',
            'landing_content.secondary_cta_text' => 'nullable|string|max:120',
            'id_entreprise' => 'nullable|exists:entreprises,id_entreprise',
            'event_link' => 'nullable|url',
            'visibility' => 'nullable|in:public,private',
            'status' => 'nullable|in:active,inactive',
            'plaquette_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'image' => 'nullable|image|max:2048',
            'partenaires' => 'nullable|array',
            'partenaires.*' => 'integer|exists:partenaires,id_partenaire',
            'new_partenaires' => 'nullable|array',
            'new_partenaires.*.nom' => 'nullable|string|max:255',
            'new_partenaires.*.type' => 'nullable|in:gold,silver,bronze,media,institutionnel,autre',
            'new_partenaires.*.email' => 'nullable|email|max:255',
            'new_partenaires.*.telephone' => 'nullable|string|max:50',
            'new_partenaires.*.site_web' => 'nullable|url|max:255',
            'new_partenaires.*.description' => 'nullable|string',
            'new_partenaires.*.logo' => 'nullable|image|max:2048',
            'ateliers' => 'nullable|array',
            'ateliers.*.titre' => 'nullable|string|max:255',
            'ateliers.*.date' => 'nullable|date',
            'ateliers.*.heure_debut' => 'nullable|date_format:H:i',
            'ateliers.*.heure_fin' => 'nullable|date_format:H:i',
            'ateliers.*.capacite' => 'nullable|integer|min:1',
            'ateliers.*.sujet' => 'nullable|string',
            'ateliers.*.banniere' => 'nullable|image|max:2048',
            'ateliers.*.speakers' => 'nullable|array',
            'ateliers.*.speakers.*' => 'integer|exists:speakers,id_speaker',
            'ateliers.*.new_speaker.nom' => 'nullable|string|max:255',
            'ateliers.*.new_speaker.prenom' => 'nullable|string|max:255',
            'ateliers.*.new_speaker.email' => 'nullable|email|max:255',
            'ateliers.*.new_speaker.poste' => 'nullable|string|max:255',
            'ateliers.*.new_speaker.company' => 'nullable|string|max:255',
            'ateliers.*.new_speaker.bio' => 'nullable|string',
            'ateliers.*.new_speaker.photo' => 'nullable|image|max:2048',
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

        $data['landing_template'] = $data['landing_template'] ?? 'template_1';
        $data['landing_content'] = array_filter($data['landing_content'] ?? [], fn ($value) => filled($value));
        $data['plaquette_pdf'] = $plaquettePath;
        $data['image'] = $imagePath;

        $evenement = null;
        DB::transaction(function () use ($request, $data, &$evenement) {
            $evenement = Evenement::create($data);

            $partenaireIds = collect($request->input('partenaires', []))
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
                ->all();
            $evenement->partenaires()->sync($partenaireIds);

            foreach ((array) $request->input('new_partenaires', []) as $index => $newPartenaire) {
                if (!filled($newPartenaire['nom'] ?? null)) {
                    continue;
                }

                $logoPath = $request->hasFile("new_partenaires.$index.logo")
                    ? $request->file("new_partenaires.$index.logo")->store('partenaires/logos', 'public')
                    : null;

                $partenaire = Partenaire::create([
                    'nom' => trim((string) ($newPartenaire['nom'] ?? '')),
                    'type' => (string) ($newPartenaire['type'] ?? 'autre'),
                    'email' => filled($newPartenaire['email'] ?? null) ? trim((string) $newPartenaire['email']) : null,
                    'telephone' => filled($newPartenaire['telephone'] ?? null) ? trim((string) $newPartenaire['telephone']) : null,
                    'description' => filled($newPartenaire['description'] ?? null) ? trim((string) $newPartenaire['description']) : null,
                    'site_web' => filled($newPartenaire['site_web'] ?? null) ? trim((string) $newPartenaire['site_web']) : null,
                    'logo' => $logoPath,
                    'actif' => true,
                ]);

                $evenement->partenaires()->syncWithoutDetaching([$partenaire->id_partenaire]);
            }

            foreach ((array) $request->input('ateliers', []) as $atelierIndex => $atelierData) {
                $hasCoreData = filled($atelierData['titre'] ?? null)
                    || filled($atelierData['date'] ?? null)
                    || filled($atelierData['heure_debut'] ?? null)
                    || filled($atelierData['heure_fin'] ?? null)
                    || filled($atelierData['capacite'] ?? null);

                if (!$hasCoreData) {
                    continue;
                }

                if (!filled($atelierData['titre'] ?? null) || !filled($atelierData['date'] ?? null) || !filled($atelierData['heure_debut'] ?? null) || !filled($atelierData['heure_fin'] ?? null) || !filled($atelierData['capacite'] ?? null)) {
                    continue;
                }

                $atelier = Atelier::create([
                    'id_event' => $evenement->id_event,
                    'titre' => trim((string) ($atelierData['titre'] ?? '')),
                    'date' => $atelierData['date'],
                    'heure_debut' => $atelierData['heure_debut'],
                    'heure_fin' => $atelierData['heure_fin'],
                    'capacite' => (int) $atelierData['capacite'],
                    'sujet' => filled($atelierData['sujet'] ?? null) ? trim((string) $atelierData['sujet']) : null,
                    'banniere' => $request->hasFile("ateliers.$atelierIndex.banniere")
                        ? $request->file("ateliers.$atelierIndex.banniere")->store('bannieres', 'public')
                        : null,
                ]);

                $speakerIds = collect((array) ($atelierData['speakers'] ?? []))
                    ->map(fn ($id) => (int) $id)
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                $newSpeakerData = (array) ($atelierData['new_speaker'] ?? []);
                $hasNewSpeaker = filled($newSpeakerData['nom'] ?? null) && filled($newSpeakerData['prenom'] ?? null);

                if ($hasNewSpeaker) {
                    $photoPath = $request->hasFile("ateliers.$atelierIndex.new_speaker.photo")
                        ? $request->file("ateliers.$atelierIndex.new_speaker.photo")->store('speakers/photos', 'public')
                        : null;

                    $speaker = Speaker::create([
                        'nom' => trim((string) ($newSpeakerData['nom'] ?? '')),
                        'prenom' => trim((string) ($newSpeakerData['prenom'] ?? '')),
                        'email' => filled($newSpeakerData['email'] ?? null) ? trim((string) $newSpeakerData['email']) : null,
                        'poste' => filled($newSpeakerData['poste'] ?? null) ? trim((string) $newSpeakerData['poste']) : null,
                        'company' => filled($newSpeakerData['company'] ?? null) ? trim((string) $newSpeakerData['company']) : null,
                        'bio' => filled($newSpeakerData['bio'] ?? null) ? trim((string) $newSpeakerData['bio']) : null,
                        'photo' => $photoPath,
                        'actif' => true,
                    ]);

                    $speakerIds[] = (int) $speaker->id_speaker;
                    $speakerIds = array_values(array_unique($speakerIds));
                }

                if (!empty($speakerIds)) {
                    $syncData = collect($speakerIds)->mapWithKeys(
                        fn ($id) => [(int) $id => ['role' => 'speaker', 'ordre' => 0]]
                    )->all();
                    $atelier->speakers()->sync($syncData);
                }
            }
        });

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
