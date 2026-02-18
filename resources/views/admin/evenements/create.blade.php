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
                    <form action="{{ route('admin.evenements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                        @csrf
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
                                <div>
                                    <label for="color_template" class="form-label">
                                        <i class="fas fa-palette mr-2 text-amber-500"></i>
                                        Template couleur landing
                                    </label>
                                    <select name="color_template"
                                            id="color_template"
                                            class="form-input-amber @error('color_template') form-input-error @enderror">
                                        <option value="violet" {{ old('color_template', 'violet') == 'violet' ? 'selected' : '' }}>Violet (par défaut)</option>
                                        <option value="ocean" {{ old('color_template') == 'ocean' ? 'selected' : '' }}>Ocean</option>
                                        <option value="sunset" {{ old('color_template') == 'sunset' ? 'selected' : '' }}>Sunset</option>
                                        <option value="forest" {{ old('color_template') == 'forest' ? 'selected' : '' }}>Forest</option>
                                        <option value="slate" {{ old('color_template') == 'slate' ? 'selected' : '' }}>Slate</option>
                                    </select>
                                    @error('color_template')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="hero_appearance" class="form-label">
                                        <i class="fas fa-layer-group mr-2 text-amber-500"></i>
                                        Apparence hero landing
                                    </label>
                                    <select name="hero_appearance"
                                            id="hero_appearance"
                                            class="form-input-amber @error('hero_appearance') form-input-error @enderror">
                                        <option value="glass_soft" {{ old('hero_appearance', 'glass_soft') == 'glass_soft' ? 'selected' : '' }}>Glass Soft</option>
                                        <option value="glass_strong" {{ old('hero_appearance') == 'glass_strong' ? 'selected' : '' }}>Glass Strong</option>
                                        <option value="clean" {{ old('hero_appearance') == 'clean' ? 'selected' : '' }}>Clean</option>
                                        <option value="cinematic" {{ old('hero_appearance') == 'cinematic' ? 'selected' : '' }}>Cinematic</option>
                                    </select>
                                    @error('hero_appearance')
                                        <p class="form-error">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- Section: Sponsors -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <div class="form-section-icon form-section-icon-indigo">
                                    <i class="fas fa-handshake text-white"></i>
                                </div>
                                <h2 class="form-section-title">Sponsors associés</h2>
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
                            <a href="{{ route('admin.evenements.index') }}" 
                               class="btn-secondary inline-flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="btn-primary inline-flex items-center justify-center">
                                <i class="fas fa-check mr-2"></i>
                                Créer l'événement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



