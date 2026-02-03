@extends('layouts.app')

@section('title', 'Modifier une Entreprise')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.entreprises.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
        <i class="fas fa-arrow-left mr-2"></i>
        Retour à la liste
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        <i class="fas fa-building mr-2 text-indigo-600"></i>
        Modifier l'Entreprise: {{ $entreprise->nom }}
    </h2>

    <form action="{{ route('admin.entreprises.update', $entreprise->id_entreprise) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-building mr-1"></i>Nom de l'entreprise *
                </label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $entreprise->nom) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-1"></i>Email *
                </label>
                <input type="email" name="email" id="email" value="{{ old('email', $entreprise->email) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Téléphone -->
            <div>
                <label for="tel" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-phone mr-1"></i>Téléphone *
                </label>
                <input type="text" name="tel" id="tel" value="{{ old('tel', $entreprise->tel) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Site Web -->
            <div>
                <label for="site_web" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-globe mr-1"></i>Site Web
                </label>
                <input type="url" name="site_web" id="site_web" value="{{ old('site_web', $entreprise->site_web) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Ville -->
            <div>
                <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-city mr-1"></i>Ville *
                </label>
                <input type="text" name="ville" id="ville" value="{{ old('ville', $entreprise->ville) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Secteur d'activité -->
            <div>
                <label for="secteur_activite" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-industry mr-1"></i>Secteur d'activité *
                </label>
                <input type="text" name="secteur_activite" id="secteur_activite" value="{{ old('secteur_activite', $entreprise->secteur_activite) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Taille entreprise -->
            <div>
                <label for="taille_entreprise" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-users mr-1"></i>Taille de l'entreprise *
                </label>
                <select name="taille_entreprise" id="taille_entreprise" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Sélectionner...</option>
                    <option value="1-10" {{ old('taille_entreprise', $entreprise->taille_entreprise) == '1-10' ? 'selected' : '' }}>1-10 employés</option>
                    <option value="11-50" {{ old('taille_entreprise', $entreprise->taille_entreprise) == '11-50' ? 'selected' : '' }}>11-50 employés</option>
                    <option value="51-200" {{ old('taille_entreprise', $entreprise->taille_entreprise) == '51-200' ? 'selected' : '' }}>51-200 employés</option>
                    <option value="201-500" {{ old('taille_entreprise', $entreprise->taille_entreprise) == '201-500' ? 'selected' : '' }}>201-500 employés</option>
                    <option value="500+" {{ old('taille_entreprise', $entreprise->taille_entreprise) == '500+' ? 'selected' : '' }}>500+ employés</option>
                </select>
            </div>

            <!-- Statut -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on mr-1"></i>Statut *
                </label>
                <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="active" {{ old('status', $entreprise->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $entreprise->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Adresse -->
            <div class="md:col-span-2">
                <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt mr-1"></i>Adresse *
                </label>
                <input type="text" name="adresse" id="adresse" value="{{ old('adresse', $entreprise->adresse) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-1"></i>Description
                </label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description', $entreprise->description) }}</textarea>
            </div>

            <!-- Logo -->
            <div class="md:col-span-2">
                <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-image mr-1"></i>Logo
                </label>
                @if($entreprise->logo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $entreprise->logo) }}" alt="Logo actuel" class="h-20 w-20 object-cover rounded">
                        <p class="text-sm text-gray-500 mt-1">Logo actuel</p>
                    </div>
                @endif
                <input type="file" name="logo" id="logo" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Formats acceptés: JPG, PNG, GIF (Max: 2MB). Laissez vide pour conserver le logo actuel.</p>
            </div>
        </div>

        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ route('admin.entreprises.index') }}" 
               class="inline-flex items-center px-3 py-2 rounded-md bg-neutral-100 text-neutral-800 hover:bg-neutral-200">
                <i class="fas fa-times mr-2"></i>Annuler
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-3 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700">
                <i class="fas fa-save mr-2"></i>Mettre à Jour
            </button>
        </div>
    </form>
</div>
@endsection
