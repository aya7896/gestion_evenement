@extends('landing.layouts.app')

@section('title', 'Inscription - ' . $evenement->titre)

@section('content')

<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <div class="flex items-center text-sm text-gray-600">
            <a href="{{ route('landing.show', $evenement->id_event) }}" class="hover:text-blue-600">
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
                    Inscription à l'événement
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
                        {{ $evenement->placesRestantes() }} places restantes
                    </span>
                </div>
            </div>
        </div>

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

                    <!-- Mot de passe -->
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

                    <!-- Confirmation mot de passe -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmer le mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Informations professionnelles -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-briefcase text-blue-600 mr-3"></i>
                    Informations professionnelles
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Entreprise -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Entreprise
                        </label>
                        <input type="text" name="company" value="{{ old('company') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company') border-red-500 @enderror">
                        @error('company')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Poste -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Poste / Fonction
                        </label>
                        <input type="text" name="poste" value="{{ old('poste') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('poste') border-red-500 @enderror">
                        @error('poste')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- LinkedIn -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Profil LinkedIn
                        </label>
                        <input type="url" name="lien_linkedin" value="{{ old('lien_linkedin') }}"
                               placeholder="https://www.linkedin.com/in/votre-profil"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lien_linkedin') border-red-500 @enderror">
                        @error('lien_linkedin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Présentation et objectifs -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-comments text-blue-600 mr-3"></i>
                    À propos de vous
                </h2>

                <!-- Photo de profil -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Photo de profil
                    </label>
                    <div class="flex items-center space-x-4">
                        <div id="preview" class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                            <i class="fas fa-user text-gray-400 text-3xl"></i>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="photo" id="photo" accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('photo') border-red-500 @enderror"
                                   onchange="previewImage(event)">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Taille max: 2MB</p>
                            @error('photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Présentation -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Présentez-vous brièvement
                    </label>
                    <textarea name="presentation" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('presentation') border-red-500 @enderror"
                              placeholder="Parlez-nous de votre parcours, vos centres d'intérêt...">{{ old('presentation') }}</textarea>
                    @error('presentation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Objectif -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Quel est votre objectif en participant à cet événement ?
                    </label>
                    <textarea name="objectif" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('objectif') border-red-500 @enderror"
                              placeholder="Networking, apprentissage, recherche de partenaires...">{{ old('objectif') }}</textarea>
                    @error('objectif')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
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
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            }
            reader.readAsDataURL(file);
        }
    }
</script>

@endsection