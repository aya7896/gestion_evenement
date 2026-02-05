@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            <!-- Header avec animation -->
            <div class="glass-card mb-6 animate-fade-in-up">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <h1 class="text-4xl font-bold text-gradient-orange mb-2">Créer une Entreprise</h1>
                            <p class="text-slate-600 dark:text-slate-400 text-lg">
                                <i class="fas fa-info-circle mr-2"></i>
                                Remplissez le formulaire pour créer une nouvelle entreprise
                            </p>
                        </div>
                        <div class="hidden md:block">
                            <span class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold bg-gradient-to-r from-orange-100 to-rose-100 dark:from-orange-900/40 dark:to-rose-900/40 text-orange-700 dark:text-orange-300 shadow-md">
                                <i class="fas fa-building mr-2 text-lg"></i>
                                Nouvelle entreprise
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire principal -->
            <div class="glass-card animate-fade-in-up stagger-1">
                <div class="p-8">
                    <!-- Messages d'erreur de validation -->
                    @if ($errors->any())
                        <div class="mb-8 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800">
                            <div class="flex items-start gap-3 mb-3">
                                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 text-xl mt-1"></i>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-red-700 dark:text-red-300 text-lg">Erreur de validation</h3>
                                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">Veuillez corriger les champs manquants ou invalides :</p>
                                    <ul class="mt-3 space-y-2">
                                        @foreach ($errors->all() as $error)
                                            <li class="text-red-600 dark:text-red-400 text-sm flex items-start gap-2">
                                                <i class="fas fa-circle-xmark text-red-500 mt-0.5 flex-shrink-0"></i>
                                                <span>{{ $error }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.entreprises.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                        @csrf

                        <!-- Section: Informations de base -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 pb-4 border-b border-slate-200 dark:border-slate-700">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-rose-500 flex items-center justify-center shadow-lg">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Informations de base</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nom -->
                                <div class="form-group">
                                    <label for="nom" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-building mr-2 text-orange-500"></i>
                                        Nom de l'entreprise
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="nom" 
                                           id="nom" 
                                           value="{{ old('nom') }}" 
                                           class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all duration-300 @error('nom') border-red-500 @enderror"
                                           placeholder="Ex: TechCorp Solutions">
                                    @error('nom')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Logo (optionnel) -->
                                <div class="form-group">
                                    <label for="logo" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-image mr-2 text-orange-500"></i>
                                        Logo de l'entreprise
                                        <span class="text-slate-400 text-sm ml-2">(optionnel)</span>
                                    </label>
                                    <input type="file"
                                           name="logo"
                                           id="logo"
                                           accept="image/*"
                                           class="w-full px-4 py-2 rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all duration-300 @error('logo') border-red-500 @enderror">
                                    @error('logo')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Secteur d'activité -->
                                <div class="form-group">
                                    <label for="secteur_activite" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-chart-line mr-2 text-orange-500"></i>
                                        Secteur d'activité
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="secteur_activite" 
                                           id="secteur_activite" 
                                           value="{{ old('secteur_activite') }}" 
                                           required
                                           class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all duration-300 @error('secteur_activite') border-red-500 @enderror"
                                           placeholder="Ex: Technologie, Finance, Santé">
                                    @error('secteur_activite')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Effectif -->
                                <div class="form-group">
                                    <label for="effectif" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-users mr-2 text-orange-500"></i>
                                        Effectif
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="effectif" 
                                           id="effectif" 
                                           value="{{ old('effectif') }}" 
                                           min="1"
                                           required
                                           class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all duration-300 @error('effectif') border-red-500 @enderror"
                                           placeholder="Ex: 50">
                                    @error('effectif')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Type d'entreprise -->
                                <div class="form-group">
                                    <label for="type_entreprise" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-tags mr-2 text-orange-500"></i>
                                        Type d'entreprise
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type_entreprise" 
                                            id="type_entreprise"
                                            required
                                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all duration-300 @error('type_entreprise') border-red-500 @enderror">
                                        <option value="">Sélectionner un type</option>
                                        <option value="startup" {{ old('type_entreprise') == 'startup' ? 'selected' : '' }}>🚀 Startup</option>
                                        <option value="PME" {{ old('type_entreprise') == 'PME' ? 'selected' : '' }}>🏢 PME</option>
                                        <option value="ETI" {{ old('type_entreprise') == 'ETI' ? 'selected' : '' }}>🏢 ETI</option>
                                        <option value="grand groupe" {{ old('type_entreprise') == 'grand groupe' ? 'selected' : '' }}>🏢 Grand Groupe</option>
                                        <option value="administration" {{ old('type_entreprise') == 'administration' ? 'selected' : '' }}>🏛️ Administration</option>
                                        <option value="association" {{ old('type_entreprise') == 'association' ? 'selected' : '' }}>🤝 Association</option>
                                    </select>
                                    @error('type_entreprise')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Coordonnées -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 pb-4 border-b border-slate-200 dark:border-slate-700">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
                                    <i class="fas fa-map-marked-alt text-white"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Coordonnées</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Adresse -->
                                <div class="form-group">
                                    <label for="adresse" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                        Adresse
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="adresse" 
                                           id="adresse" 
                                           value="{{ old('adresse') }}"
                                           required
                                           class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 @error('adresse') border-red-500 @enderror"
                                           placeholder="Ex: 123 Avenue des Champs-Élysées">
                                    @error('adresse')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Ville -->
                                <div class="form-group">
                                    <label for="ville" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-city mr-2 text-blue-500"></i>
                                        Ville
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="ville" 
                                           id="ville" 
                                           value="{{ old('ville') }}"
                                           required
                                           class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 @error('ville') border-red-500 @enderror"
                                           placeholder="Ex: Paris">
                                    @error('ville')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Code postal -->
                                <div class="form-group">
                                    <label for="code_postal" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-mailbox mr-2 text-blue-500"></i>
                                        Code postal
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="code_postal" 
                                           id="code_postal" 
                                           value="{{ old('code_postal') }}"
                                           required
                                           class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 @error('code_postal') border-red-500 @enderror"
                                           placeholder="Ex: 75008">
                                    @error('code_postal')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Pays -->
                                <div class="form-group">
                                    <label for="pays" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-globe mr-2 text-blue-500"></i>
                                        Pays
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="pays" 
                                           id="pays" 
                                           value="{{ old('pays') }}"
                                           required
                                           class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 @error('pays') border-red-500 @enderror"
                                           placeholder="Ex: France">
                                    @error('pays')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Téléphone -->
                                <div class="form-group">
                                    <label for="telephone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-phone mr-2 text-blue-500"></i>
                                        Téléphone
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" 
                                           name="tel" 
                                           id="tel" 
                                           value="{{ old('tel') }}" 
                                           class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 @error('tel') border-red-500 @enderror"
                                           placeholder="Ex: +33 1 23 45 67 89">
                                    @error('tel')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                        Email
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           value="{{ old('email') }}" 
                                           class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 @error('email') border-red-500 @enderror"
                                           placeholder="Ex: contact@entreprise.com">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Site web -->
                                <div class="form-group">
                                    <label for="site_web" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-globe mr-2 text-blue-500"></i>
                                        Site web
                                    </label>
                                    <input type="url" 
                                           name="site_web" 
                                           id="site_web" 
                                           value="{{ old('site_web') }}" 
                                           class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 @error('site_web') border-red-500 @enderror"
                                           placeholder="Ex: https://entreprise.com">
                                    @error('site_web')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Description -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 pb-4 border-b border-slate-200 dark:border-slate-700">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                                    <i class="fas fa-align-left text-white"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Description</h2>
                            </div>

                            <div class="form-group">
                                <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                    <i class="fas fa-file-lines mr-2 text-purple-500"></i>
                                    Description de l'entreprise
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="4" 
                                          class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 transition-all duration-300 resize-none @error('description') border-red-500 @enderror"
                                          placeholder="Décrivez votre entreprise : mission, valeurs, histoire...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Section: Paramètres -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 pb-4 border-b border-slate-200 dark:border-slate-700">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                                    <i class="fas fa-sliders text-white"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Paramètres</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Statut -->
                                <div class="form-group">
                                    <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-toggle-on mr-2 text-amber-500"></i>
                                        Statut
                                    </label>
                                    <select name="status" 
                                            id="status" 
                                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/20 transition-all duration-300 @error('status') border-red-500 @enderror">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>✅ Actif</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>❌ Inactif</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Validation Super Admin -->
                                <div class="form-group">
                                    <label for="validation_superAdmin" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        <i class="fas fa-shield-alt mr-2 text-amber-500"></i>
                                        Validation Super Admin
                                    </label>
                                    <select name="validation_superAdmin" 
                                            id="validation_superAdmin" 
                                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/20 transition-all duration-300 @error('validation_superAdmin') border-red-500 @enderror">
                                        <option value="0" {{ old('validation_superAdmin', 0) == 0 ? 'selected' : '' }}>⏳ Non validé</option>
                                        <option value="1" {{ old('validation_superAdmin') == 1 ? 'selected' : '' }}>✅ Validé</option>
                                    </select>
                                    <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Réservé aux super administrateurs
                                    </p>
                                    @error('validation_superAdmin')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t-2 border-slate-200 dark:border-slate-700">
                            <a href="{{ route('admin.entreprises.index') }}" 
                               class="btn-secondary inline-flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="btn-primary inline-flex items-center justify-center">
                                <i class="fas fa-check mr-2"></i>
                                Créer l'entreprise
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection