@extends('landing.layouts.app')

@section('title', 'Inscription - ' . $evenement->titre)

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <div class="flex items-center text-sm text-gray-600">
            <a href="{{ route('public.evenement.landing', $evenement->id_event) }}" class="hover:text-blue-600">
                <i class="fas fa-arrow-left mr-2"></i>Retour à l'événement
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        
        <!-- En-tête -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Formulaire d'inscription
                </h1>
                <p class="text-xl text-gray-600 mb-4">{{ $evenement->titre }}</p>
                
                <!-- Infos rapides -->
                <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-600">
                    <span>
                        <i class="far fa-calendar mr-1"></i>
                        {{ $evenement->date_heure_debut->format('d/m/Y') }}
                    </span>
                    <span>
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        {{ $evenement->lieu }}
                    </span>
                    <span>
                        <i class="fas fa-users mr-1"></i>
                        {{ $evenement->capacite }} places
                    </span>
                </div>
            </div>
        </div>

        <!-- Messages d'erreur -->
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p class="font-bold mb-2">Veuillez corriger les erreurs suivantes :</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire d'inscription -->
        <form action="{{ route('inscription.store', $evenement->id_event) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Informations personnelles -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-3"></i>
                    Informations personnelles
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom') border-red-500 @enderror">
                        @error('nom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prénom -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prenom') border-red-500 @enderror">
                        @error('prenom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="telephone" value="{{ old('telephone') }}" required
                               placeholder="+212 6XX XX XX XX"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-500 @enderror">
                        @error('telephone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Entreprise / Organisation
                        </label>
                        <input type="text" name="company" value="{{ old('company') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company') border-red-500 @enderror">
                        @error('company')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sélection des Ateliers -->
            @if($evenement->ateliers->count() > 0)
                <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-chalkboard-teacher text-blue-600 mr-3"></i>
                        Sélectionnez vos ateliers
                    </h2>
                    <p class="text-gray-600 mb-6">Choisissez les ateliers auxquels vous souhaitez participer</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($evenement->ateliers as $atelier)
                            <label class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-400 cursor-pointer transition">
                                <input type="checkbox" name="ateliers[]" value="{{ $atelier->id_atelier }}" 
                                       {{ old('ateliers') && in_array($atelier->id_atelier, old('ateliers', [])) ? 'checked' : '' }}
                                       class="mt-1 mr-3 h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">{{ $atelier->titre }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($atelier->sujet ?? $atelier->description, 60) }}</p>
                                    <div class="flex gap-3 mt-2 text-xs text-gray-500">
                                        @if($atelier->date)
                                            <span><i class="far fa-calendar mr-1"></i>{{ $atelier->date->format('d/m/Y') }}</span>
                                        @endif
                                        @if($atelier->heure_debut)
                                            <span><i class="far fa-clock mr-1"></i>{{ $atelier->heure_debut->format('H:i') }}</span>
                                        @endif
                                        @if($atelier->capacite)
                                            <span><i class="fas fa-users mr-1"></i>{{ $atelier->capacite }} places</span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- ...existing code... (remove ateliers section) -->

            <!-- Checkbox One-to-One -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" name="one_to_one" id="one_to_one" value="1" {{ old('one_to_one') ? 'checked' : '' }}
                        class="mt-1 mr-3 h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <div>
                        <span class="font-semibold text-gray-800">Je souhaite participer à une session one-to-one</span>
                        <p class="text-sm text-gray-600">Vous serez contacté(e) pour fixer un rendez-vous personnalisé</p>
                    </div>
                </label>
            </div>

            <!-- Champs avancés, affichés seulement si one-to-one est coché -->
            <div id="advanced-fields" class="bg-white rounded-lg shadow-lg p-8 mb-6" style="display: none;">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-user-cog text-blue-600 mr-3"></i>
                    Informations complémentaires (one-to-one)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Photo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Photo</label>
                        <input type="file" name="photo" accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('photo') border-red-500 @enderror">
                        @error('photo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Poste -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Poste</label>
                        <input type="text" name="poste" value="{{ old('poste') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('poste') border-red-500 @enderror">
                        @error('poste')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Lien LinkedIn -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lien LinkedIn</label>
                        <input type="text" name="lien_linkedin" value="{{ old('lien_linkedin') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lien_linkedin') border-red-500 @enderror">
                        @error('lien_linkedin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Présentation -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Présentation</label>
                        <textarea name="presentation" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('presentation') border-red-500 @enderror">{{ old('presentation') }}</textarea>
                        @error('presentation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Objectif -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Objectif</label>
                        <textarea name="objectif" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('objectif') border-red-500 @enderror">{{ old('objectif') }}</textarea>
                        @error('objectif')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Conditions et validation -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <div class="flex items-start mb-6">
                    <input type="checkbox" id="terms" required
                           class="mt-1 mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="terms" class="text-sm text-gray-700">
                        J'accepte les <a href="#" class="text-blue-600 hover:underline">conditions générales</a> 
                        et la <a href="#" class="text-blue-600 hover:underline">politique de confidentialité</a>
                        <span class="text-red-500">*</span>
                    </label>
                </div>

                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                    <p class="text-sm text-green-800">
                        <i class="fas fa-check-circle mr-2"></i>
                        Après validation, vous recevrez par email :
                    </p>
                    <ul class="list-disc list-inside text-sm text-green-800 mt-2 ml-4">
                        <li>Votre badge nominatif avec QR code</li>
                        <li>La plaquette complète de l'événement</li>
                        <li>Les détails de vos ateliers sélectionnés</li>
                    </ul>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-4 rounded-lg font-semibold text-lg hover:bg-blue-700 transition shadow-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    Confirmer mon inscription
                </button>
            </div>

        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('one_to_one');
    const advancedFields = document.getElementById('advanced-fields');
    // List of advanced field names to toggle required
    const requiredFields = [
        'photo',
        'poste',
        'lien_linkedin',
        'presentation',
        'objectif'
    ];
    function toggleFields() {
        const show = checkbox.checked;
        advancedFields.style.display = show ? 'block' : 'none';
        requiredFields.forEach(function(name) {
            const el = document.querySelector('[name="' + name + '"]');
            if (el) {
                if (show) {
                    el.setAttribute('required', 'required');
                } else {
                    el.removeAttribute('required');
                }
            }
        });
    }
    checkbox.addEventListener('change', toggleFields);
    toggleFields(); // Initial state
});
</script>
@endsection