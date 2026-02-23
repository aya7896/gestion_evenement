@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="form-header-card">
                <div class="form-header-content">
                    <div class="flex-1">
                        <h1 class="form-title">Créer un Événement</h1>
                        <p class="form-subtitle">
                            <i class="fas fa-info-circle mr-2"></i>
                            Remplissez le formulaire pour créer un nouvel événement professionnel
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <span class="form-badge-new">
                            <i class="fas fa-calendar-plus mr-2 text-lg"></i>
                            Nouvel événement
                        </span>
                    </div>
                </div>
            </div>

            <!-- Formulaire principal -->
            <div class="glass-card animate-fade-in-up stagger-1">
                <div class="p-8">
                    <form action="{{ route('evenements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                        @csrf
                        <div class="mb-6 rounded-xl border border-neutral-200 bg-white/80 p-4">
                            <div class="flex items-center justify-between text-sm font-semibold">
                                <span id="step-label-1" class="text-indigo-600">Etape 1: Infos evenement, sponsors, fichiers</span>
                                <span id="step-label-2" class="text-neutral-400">Etape 2: Template et personnalisation</span>
                            </div>
                            <div class="mt-3 h-2 w-full rounded-full bg-neutral-200">
                                <div id="step-progress" class="h-2 rounded-full bg-indigo-500 transition-all duration-300" style="width: 50%"></div>
                            </div>
                            <p class="mt-2 text-xs text-neutral-600">Les ateliers sont lies a l evenement apres creation.</p>
                        </div>

                        <!-- Section: Informations de base -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <div class="form-section-icon form-section-icon-orange">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <h2 class="form-section-title">Informations de base</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Titre -->
                                <div>
                                    <label for="titre" class="form-label">
                                        <i class="fas fa-heading mr-2 text-orange-500"></i>
                                        Titre de l'événement
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="titre" 
                                           id="titre" 
                                           value="{{ old('titre') }}" 
                                           class="form-input @error('titre') form-input-error @enderror"
                                           placeholder="Ex: Conférence annuelle 2025">
                                    @error('titre')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Type -->
                                <div>
                                    <label for="type" class="form-label">
                                        <i class="fas fa-tags mr-2 text-orange-500"></i>
                                        Type d'événement
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" 
                                            id="type" 
                                            class="form-input @error('type') form-input-error @enderror">
                                        <option value="">Sélectionner un type</option>
                                        <option value="conférence" {{ old('type') == 'conférence' ? 'selected' : '' }}>📊 Conférence</option>
                                        <option value="workshop" {{ old('type') == 'workshop' ? 'selected' : '' }}>🛠️ Workshop</option>
                                        <option value="séminaire" {{ old('type') == 'séminaire' ? 'selected' : '' }}>🎓 Séminaire</option>
                                        <option value="formation" {{ old('type') == 'formation' ? 'selected' : '' }}>📚 Formation</option>
                                        <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>✨ Autre</option>
                                    </select>
                                    @error('type')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Capacité -->
                                <div>
                                    <label for="capacite" class="form-label">
                                        <i class="fas fa-users mr-2 text-orange-500"></i>
                                        Capacité maximale
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="capacite" 
                                           id="capacite" 
                                           value="{{ old('capacite') }}" 
                                           min="1"
                                           class="form-input @error('capacite') form-input-error @enderror"
                                           placeholder="Ex: 100">
                                    @error('capacite')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Mode -->
                                <div>
                                    <label for="mode" class="form-label">
                                        <i class="fas fa-laptop-house mr-2 text-orange-500"></i>
                                        Mode de participation
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select name="mode" 
                                            id="mode" 
                                            class="form-input @error('mode') form-input-error @enderror">
                                        <option value="présentiel" {{ old('mode', 'présentiel') == 'présentiel' ? 'selected' : '' }}>🏢 Présentiel</option>
                                        <option value="en ligne" {{ old('mode') == 'en ligne' ? 'selected' : '' }}>💻 En ligne</option>
                                        <option value="hybride" {{ old('mode') == 'hybride' ? 'selected' : '' }}>🔄 Hybride</option>
                                    </select>
                                    @error('mode')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Localisation -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <div class="form-section-icon form-section-icon-blue">
                                    <i class="fas fa-map-marked-alt text-white"></i>
                                </div>
                                <h2 class="form-section-title">Localisation et lieu</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Localisation -->
                                <div>
                                    <label for="localisation" class="form-label">
                                        <i class="fas fa-globe mr-2 text-blue-500"></i>
                                        Localisation
                                    </label>
                                    <input type="text" 
                                           name="localisation" 
                                           id="localisation" 
                                           value="{{ old('localisation') }}" 
                                           class="form-input-blue @error('localisation') form-input-error @enderror"
                                           placeholder="Ex: Paris, Île-de-France, France">
                                    @error('localisation')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Lieu -->
                                <div>
                                    <label for="lieu" class="form-label">
                                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                        Lieu précis
                                    </label>
                                    <input type="text" 
                                           name="lieu" 
                                           id="lieu" 
                                           value="{{ old('lieu') }}" 
                                           class="form-input-blue @error('lieu') form-input-error @enderror"
                                           placeholder="Ex: Palais des Congrès, 2 Place de la Porte Maillot">
                                    @error('lieu')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Dates et heures -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <div class="form-section-icon form-section-icon-green">
                                    <i class="fas fa-calendar-days text-white"></i>
                                </div>
                                <h2 class="form-section-title">Dates et horaires</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Date début -->
                                <div>
                                    <label for="date_heure_debut" class="form-label">
                                        <i class="fas fa-calendar-plus mr-2 text-emerald-500"></i>
                                        Date et heure de début
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" 
                                           name="date_heure_debut" 
                                           id="date_heure_debut" 
                                           value="{{ old('date_heure_debut') }}" 
                                           class="form-input-green @error('date_heure_debut') form-input-error @enderror">
                                    @error('date_heure_debut')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Date fin -->
                                <div>
                                    <label for="date_heure_fin" class="form-label">
                                        <i class="fas fa-calendar-check mr-2 text-emerald-500"></i>
                                        Date et heure de fin
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" 
                                           name="date_heure_fin" 
                                           id="date_heure_fin" 
                                           value="{{ old('date_heure_fin') }}" 
                                           class="form-input-green @error('date_heure_fin') form-input-error @enderror">
                                    @error('date_heure_fin')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Description -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <div class="form-section-icon form-section-icon-purple">
                                    <i class="fas fa-align-left text-white"></i>
                                </div>
                                <h2 class="form-section-title">Description détaillée</h2>
                            </div>

                            <div>
                                <label for="description" class="form-label">
                                    <i class="fas fa-file-lines mr-2 text-purple-500"></i>
                                    Description de l'événement
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="6" 
                                          class="form-input-purple @error('description') form-input-error @enderror"
                                          placeholder="Décrivez votre événement en détail : objectifs, programme, public cible...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="form-error">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Section: Paramètres -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <div class="form-section-icon form-section-icon-amber">
                                    <i class="fas fa-sliders text-white"></i>
                                </div>
                                <h2 class="form-section-title">Paramètres et options</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <!-- Lien événement -->
                                <div>
                                    <label for="event_link" class="form-label">
                                        <i class="fas fa-link mr-2 text-amber-500"></i>
                                        Lien de l'événement
                                    </label>
                                    <input type="url" 
                                           name="event_link" 
                                           id="event_link" 
                                           value="{{ old('event_link') }}" 
                                           class="form-input-amber @error('event_link') form-input-error @enderror"
                                           placeholder="https://example.com">
                                    <p class="form-help">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Pour événements en ligne ou hybrides
                                    </p>
                                    @error('event_link')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Visibilité -->
                                <div>
                                    <label for="visibility" class="form-label">
                                        <i class="fas fa-eye mr-2 text-amber-500"></i>
                                        Visibilité
                                    </label>
                                    <select name="visibility" 
                                            id="visibility" 
                                            class="form-input-amber @error('visibility') form-input-error @enderror">
                                        <option value="public" {{ old('visibility', 'public') == 'public' ? 'selected' : '' }}>🌐 Public</option>
                                        <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>🔒 Privé</option>
                                    </select>
                                    @error('visibility')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Statut -->
                                <div>
                                    <label for="status" class="form-label">
                                        <i class="fas fa-toggle-on mr-2 text-amber-500"></i>
                                        Statut
                                    </label>
                                    <select name="status" 
                                            id="status" 
                                            class="form-input-amber @error('status') form-input-error @enderror">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>✅ Actif</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>❌ Inactif</option>
                                    </select>
                                    @error('status')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-header">
                                <div class="form-section-icon form-section-icon-indigo">
                                    <i class="fas fa-window-restore text-white"></i>
                                </div>
                                <h2 class="form-section-title">Landing Template</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach(($landingTemplates ?? []) as $key => $template)
                                    <label class="p-4 rounded-xl border cursor-pointer transition {{ old('landing_template', 'template_1') === $key ? 'border-indigo-500 bg-indigo-50/80' : 'border-neutral-200 hover:border-indigo-300' }}">
                                        <input type="radio" name="landing_template" value="{{ $key }}" class="mr-2" {{ old('landing_template', 'template_1') === $key ? 'checked' : '' }}>
                                        <span class="font-semibold">{{ $template['name'] ?? $key }}</span>
                                        <p class="text-sm text-neutral-600 mt-1">{{ $template['description'] ?? '' }}</p>
                                        <a class="text-sm text-indigo-600 hover:underline mt-2 inline-block" target="_blank" href="{{ route('landing.templates.preview', $key) }}">
                                            Apercu
                                        </a>
                                    </label>
                                @endforeach
                            </div>
                            @error('landing_template')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                                <div>
                                    <label for="landing_hero_title" class="form-label">Titre hero (optionnel)</label>
                                    <input id="landing_hero_title" type="text" name="landing_content[hero_title]" value="{{ old('landing_content.hero_title') }}" class="form-input">
                                    <p class="form-help">Defaut: titre de l evenement.</p>
                                </div>
                                <div>
                                    <label for="landing_hero_subtitle" class="form-label">Sous-titre hero (optionnel)</label>
                                    <textarea id="landing_hero_subtitle" name="landing_content[hero_subtitle]" rows="3" class="form-input">{{ old('landing_content.hero_subtitle') }}</textarea>
                                    <p class="form-help">Defaut: description de l evenement.</p>
                                </div>
                                <div>
                                    <label for="landing_primary_cta_text" class="form-label">Texte bouton principal (optionnel)</label>
                                    <input id="landing_primary_cta_text" type="text" name="landing_content[primary_cta_text]" value="{{ old('landing_content.primary_cta_text') }}" class="form-input">
                                </div>
                                <div>
                                    <label for="landing_secondary_cta_text" class="form-label">Texte bouton secondaire (optionnel)</label>
                                    <input id="landing_secondary_cta_text" type="text" name="landing_content[secondary_cta_text]" value="{{ old('landing_content.secondary_cta_text') }}" class="form-input">
                                </div>
                            </div>
                        </div>

                        <!-- Section: Sponsors -->
                        <div class="form-section">
                            <div class="form-section-header justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="form-section-icon form-section-icon-indigo">
                                        <i class="fas fa-handshake text-white"></i>
                                    </div>
                                    <h2 class="form-section-title">Sponsors associés</h2>
                                </div>
                                <button type="button" id="add-sponsor-btn" class="btn-secondary inline-flex items-center">
                                    <i class="fas fa-plus mr-2"></i> Ajouter un sponsor
                                </button>
                            </div>
                            <p class="form-help mb-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                Sélectionnez un ou plusieurs sponsors pour cet evenement.
                            </p>
                            @php
                                $selectedSponsors = old('partenaires', []);
                            @endphp
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @forelse(($partenaires ?? collect()) as $partenaire)
                                    <label class="flex items-center gap-3 p-3 rounded-lg border border-neutral-200 dark:border-neutral-700 hover:border-indigo-400 transition">
                                        <input type="checkbox"
                                               name="partenaires[]"
                                               value="{{ $partenaire->id_partenaire }}"
                                               class="rounded border-neutral-300 text-indigo-600 focus:ring-indigo-500"
                                               {{ in_array($partenaire->id_partenaire, $selectedSponsors) ? 'checked' : '' }}>
                                        <span class="text-sm text-neutral-800 dark:text-neutral-200">
                                            {{ $partenaire->nom }} <span class="text-neutral-500">({{ strtoupper($partenaire->type) }})</span>
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-sm text-neutral-500">Aucun sponsor actif disponible.</p>
                                @endforelse
                            </div>
                            @error('partenaires')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            @error('partenaires.*')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <div id="new-sponsors-container" class="space-y-4 mt-4"></div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-header justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="form-section-icon form-section-icon-blue">
                                        <i class="fas fa-chalkboard-teacher text-white"></i>
                                    </div>
                                    <h2 class="form-section-title">Ateliers et speakers (optionnel)</h2>
                                </div>
                                <button type="button" id="add-atelier-btn" class="btn-secondary inline-flex items-center">
                                    <i class="fas fa-plus mr-2"></i> Ajouter un atelier
                                </button>
                            </div>
                            <p class="form-help mb-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                Creez des ateliers au moment de la creation de l evenement, puis assignez des speakers.
                            </p>
                            <div id="ateliers-container" class="space-y-6 mt-4"></div>
                        </div>

                        <!-- Section: Admin (si super_admin) -->
                        @if(auth()->user()->role === 'super_admin')
                        <div class="form-section">
                            <div class="form-section-header">
                                <div class="form-section-icon form-section-icon-red">
                                    <i class="fas fa-shield-halved text-white"></i>
                                </div>
                                <h2 class="form-section-title">Administration</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Entreprise -->
                                <div>
                                    <label for="id_entreprise" class="form-label">
                                        <i class="fas fa-building mr-2 text-red-500"></i>
                                        Entreprise
                                    </label>
                                    <select name="id_entreprise" 
                                            id="id_entreprise" 
                                            class="form-input-red @error('id_entreprise') form-input-error @enderror">
                                        <option value="">Sélectionner une entreprise</option>
                                        @foreach($entreprises ?? [] as $entreprise)
                                            <option value="{{ $entreprise->id_entreprise }}" {{ old('id_entreprise') == $entreprise->id_entreprise ? 'selected' : '' }}>
                                                {{ $entreprise->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_entreprise')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        @endif

                        <!-- Section: Fichiers -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <div class="form-section-icon form-section-icon-indigo">
                                    <i class="fas fa-cloud-arrow-up text-white"></i>
                                </div>
                                <h2 class="form-section-title">Documents et médias</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Plaquette PDF -->
                                <div>
                                    <label for="plaquette_pdf" class="form-label">
                                        <i class="fas fa-file-pdf mr-2 text-indigo-500"></i>
                                        Plaquette PDF
                                    </label>
                                    <input type="file" 
                                           name="plaquette_pdf" 
                                           id="plaquette_pdf" 
                                           accept="application/pdf"
                                           class="form-file-input @error('plaquette_pdf') form-input-error @enderror">
                                    <p class="form-help">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Format PDF uniquement, max 5MB
                                    </p>
                                    @error('plaquette_pdf')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Image de couverture -->
                                <div>
                                    <label for="image" class="form-label">
                                        <i class="fas fa-image mr-2 text-indigo-500"></i>
                                        Image de couverture
                                    </label>
                                    <input type="file" 
                                           name="image" 
                                           id="image" 
                                           accept="image/*"
                                           class="form-file-input @error('image') form-input-error @enderror">
                                    <p class="form-help">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Formats: JPG, PNG, GIF. Max 2MB
                                    </p>
                                    @error('image')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="form-actions">
                            <a href="{{ route('evenements.index') }}" 
                               class="btn-secondary inline-flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Annuler
                            </a>
                            <button type="button"
                                    id="prev-step-btn"
                                    class="btn-secondary inline-flex items-center justify-center hidden">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Precedent
                            </button>
                            <button type="button"
                                    id="next-step-btn"
                                    class="btn-primary inline-flex items-center justify-center">
                                Suivant
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                            <button type="submit"
                                    id="submit-step-btn"
                                    class="btn-primary inline-flex items-center justify-center hidden">
                                <i class="fas fa-check mr-2"></i>
                                Créer l'événement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @php
        $speakerOptionsJson = collect($speakers ?? [])->map(function ($speaker) {
            return [
                'id' => $speaker->id_speaker,
                'name' => trim(($speaker->prenom ?? '') . ' ' . ($speaker->nom ?? '')),
                'poste' => $speaker->poste,
                'company' => $speaker->company,
            ];
        })->values();
    @endphp
    <script>
        (function () {
            const allSections = Array.from(document.querySelectorAll('.form-section'));
            const landingSection = allSections.find((section) => section.textContent.includes('Landing Template'));
            if (!landingSection) return;

            const step1Sections = allSections.filter((section) => section !== landingSection);
            const step2Sections = [landingSection];

            const stepLabel1 = document.getElementById('step-label-1');
            const stepLabel2 = document.getElementById('step-label-2');
            const stepProgress = document.getElementById('step-progress');
            const nextBtn = document.getElementById('next-step-btn');
            const prevBtn = document.getElementById('prev-step-btn');
            const submitBtn = document.getElementById('submit-step-btn');

            const speakers = @json($speakerOptionsJson);
            const sponsorTypes = @json($partenaireTypes ?? []);

            const ateliersContainer = document.getElementById('ateliers-container');
            const sponsorsContainer = document.getElementById('new-sponsors-container');
            const addAtelierBtn = document.getElementById('add-atelier-btn');
            const addSponsorBtn = document.getElementById('add-sponsor-btn');

            let currentStep = 1;
            let atelierIndex = 0;
            let sponsorIndex = 0;

            function renderStep() {
                const showStep1 = currentStep === 1;

                step1Sections.forEach((section) => {
                    section.style.display = showStep1 ? '' : 'none';
                });

                step2Sections.forEach((section) => {
                    section.style.display = showStep1 ? 'none' : '';
                });

                stepLabel1.classList.toggle('text-indigo-600', showStep1);
                stepLabel1.classList.toggle('text-neutral-400', !showStep1);
                stepLabel2.classList.toggle('text-indigo-600', !showStep1);
                stepLabel2.classList.toggle('text-neutral-400', showStep1);

                stepProgress.style.width = showStep1 ? '50%' : '100%';
                nextBtn.classList.toggle('hidden', !showStep1);
                prevBtn.classList.toggle('hidden', showStep1);
                submitBtn.classList.toggle('hidden', showStep1);
            }

            function buildSpeakerOptions(index) {
                if (!Array.isArray(speakers) || speakers.length === 0) {
                    return '<p class="text-sm text-neutral-500">Aucun speaker actif disponible.</p>';
                }

                return speakers.map((speaker) => {
                    const titleParts = [speaker.poste, speaker.company].filter(Boolean).join(' - ');
                    return `
                        <label class="flex items-center gap-2 text-sm text-neutral-700">
                            <input type="checkbox" name="ateliers[${index}][speakers][]" value="${speaker.id}" class="rounded border-neutral-300 text-indigo-600 focus:ring-indigo-500">
                            <span>${speaker.name}${titleParts ? ` <span class="text-neutral-500">(${titleParts})</span>` : ''}</span>
                        </label>
                    `;
                }).join('');
            }

            function buildSponsorTypeOptions() {
                return Object.entries(sponsorTypes).map(([value, label]) => `<option value="${value}">${label}</option>`).join('');
            }

            function addAtelierRow() {
                if (!ateliersContainer) return;
                const index = atelierIndex++;
                const row = document.createElement('div');
                row.className = 'rounded-xl border border-neutral-200 bg-white/70 p-4 space-y-4';
                row.innerHTML = `
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-neutral-800">Atelier #${index + 1}</h3>
                        <button type="button" class="text-red-500 hover:text-red-700 text-sm remove-atelier">Supprimer</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Titre atelier</label>
                            <input type="text" name="ateliers[${index}][titre]" class="form-input" placeholder="Titre de l atelier">
                        </div>
                        <div>
                            <label class="form-label">Date</label>
                            <input type="date" name="ateliers[${index}][date]" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Heure debut</label>
                            <input type="time" name="ateliers[${index}][heure_debut]" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Heure fin</label>
                            <input type="time" name="ateliers[${index}][heure_fin]" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Capacite</label>
                            <input type="number" min="1" name="ateliers[${index}][capacite]" class="form-input" placeholder="Nombre de places">
                        </div>
                        <div>
                            <label class="form-label">Banniere atelier</label>
                            <input type="file" name="ateliers[${index}][banniere]" accept="image/*" class="form-file-input">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Sujet / Description atelier</label>
                        <textarea name="ateliers[${index}][sujet]" rows="3" class="form-input" placeholder="Sujet de l atelier"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Speakers existants</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                            ${buildSpeakerOptions(index)}
                        </div>
                    </div>
                    <div class="rounded-lg border border-dashed border-indigo-300 p-3 bg-indigo-50/40">
                        <h4 class="text-sm font-semibold text-indigo-700 mb-2">Ajouter un nouveau speaker pour cet atelier (optionnel)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <input type="text" name="ateliers[${index}][new_speaker][prenom]" class="form-input" placeholder="Prenom">
                            <input type="text" name="ateliers[${index}][new_speaker][nom]" class="form-input" placeholder="Nom">
                            <input type="email" name="ateliers[${index}][new_speaker][email]" class="form-input" placeholder="Email">
                            <input type="text" name="ateliers[${index}][new_speaker][poste]" class="form-input" placeholder="Poste">
                            <input type="text" name="ateliers[${index}][new_speaker][company]" class="form-input" placeholder="Entreprise">
                            <input type="file" name="ateliers[${index}][new_speaker][photo]" class="form-file-input" accept="image/*">
                        </div>
                        <textarea name="ateliers[${index}][new_speaker][bio]" rows="2" class="form-input mt-3" placeholder="Bio courte"></textarea>
                    </div>
                `;
                ateliersContainer.appendChild(row);

                row.querySelector('.remove-atelier')?.addEventListener('click', () => row.remove());
            }

            function addSponsorRow() {
                if (!sponsorsContainer) return;
                const index = sponsorIndex++;
                const row = document.createElement('div');
                row.className = 'rounded-xl border border-neutral-200 bg-white/70 p-4 space-y-3';
                row.innerHTML = `
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-neutral-800">Sponsor #${index + 1}</h3>
                        <button type="button" class="text-red-500 hover:text-red-700 text-sm remove-sponsor">Supprimer</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input type="text" name="new_partenaires[${index}][nom]" class="form-input" placeholder="Nom sponsor">
                        <select name="new_partenaires[${index}][type]" class="form-input">
                            <option value="">Type</option>
                            ${buildSponsorTypeOptions()}
                        </select>
                        <input type="email" name="new_partenaires[${index}][email]" class="form-input" placeholder="Email">
                        <input type="text" name="new_partenaires[${index}][telephone]" class="form-input" placeholder="Telephone">
                        <input type="url" name="new_partenaires[${index}][site_web]" class="form-input" placeholder="Site web">
                        <input type="file" name="new_partenaires[${index}][logo]" class="form-file-input" accept="image/*">
                    </div>
                    <textarea name="new_partenaires[${index}][description]" rows="2" class="form-input" placeholder="Description"></textarea>
                `;
                sponsorsContainer.appendChild(row);
                row.querySelector('.remove-sponsor')?.addEventListener('click', () => row.remove());
            }

            nextBtn.addEventListener('click', () => {
                currentStep = 2;
                renderStep();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            prevBtn.addEventListener('click', () => {
                currentStep = 1;
                renderStep();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            addAtelierBtn?.addEventListener('click', addAtelierRow);
            addSponsorBtn?.addEventListener('click', addSponsorRow);

            renderStep();
        })();
    </script>
@endsection



