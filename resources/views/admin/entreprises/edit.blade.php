@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto">
        <x-card>
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Modifier l'Entreprise</h1>

        <form action="{{ route('admin.entreprises.update', $entreprise) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom de l'entreprise</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom', $entreprise->nom) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $entreprise->email) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tel" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                    <input type="text" name="tel" id="tel" value="{{ old('tel', $entreprise->tel) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('tel')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="site_web" class="block text-sm font-medium text-gray-700 mb-2">Site Web</label>
                    <input type="url" name="site_web" id="site_web" value="{{ old('site_web', $entreprise->site_web) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('site_web')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="secteur_activite" class="block text-sm font-medium text-gray-700 mb-2">Secteur d'activité</label>
                    <input type="text" name="secteur_activite" id="secteur_activite" value="{{ old('secteur_activite', $entreprise->secteur_activite) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('secteur_activite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="taille_entreprise" class="block text-sm font-medium text-gray-700 mb-2">Taille de l'entreprise (calculée automatiquement)</label>
                    <input type="text" id="taille_entreprise" 
                           value="{{ $entreprise->taille_entreprise }}" 
                           disabled
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-600">
                    <p class="mt-1 text-sm text-gray-500">La taille est calculée automatiquement en fonction du nombre d'employés.</p>
                </div>

                <div class="md:col-span-2">
                    <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                    <input type="text" name="adresse" id="adresse" value="{{ old('adresse', $entreprise->adresse) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('adresse')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                    <input type="text" name="ville" id="ville" value="{{ old('ville', $entreprise->ville) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('ville')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="code_postal" class="block text-sm font-medium text-gray-700 mb-2">Code postal</label>
                    <input type="text" name="code_postal" id="code_postal" value="{{ old('code_postal', $entreprise->code_postal) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('code_postal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pays" class="block text-sm font-medium text-gray-700 mb-2">Pays</label>
                    <input type="text" name="pays" id="pays" value="{{ old('pays', $entreprise->pays) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('pays')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type_entreprise" class="block text-sm font-medium text-gray-700 mb-2">Type d'entreprise</label>
                    <select name="type_entreprise" id="type_entreprise" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Sélectionner un type</option>
                        <option value="startup" {{ old('type_entreprise', $entreprise->type_entreprise) == 'startup' ? 'selected' : '' }}>Startup</option>
                        <option value="PME" {{ old('type_entreprise', $entreprise->type_entreprise) == 'PME' ? 'selected' : '' }}>PME</option>
                        <option value="ETI" {{ old('type_entreprise', $entreprise->type_entreprise) == 'ETI' ? 'selected' : '' }}>ETI</option>
                        <option value="grand groupe" {{ old('type_entreprise', $entreprise->type_entreprise) == 'grand groupe' ? 'selected' : '' }}>Grand Groupe</option>
                        <option value="administration" {{ old('type_entreprise', $entreprise->type_entreprise) == 'administration' ? 'selected' : '' }}>Administration</option>
                        <option value="association" {{ old('type_entreprise', $entreprise->type_entreprise) == 'association' ? 'selected' : '' }}>Association</option>
                    </select>
                    @error('type_entreprise')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="effectif" class="block text-sm font-medium text-gray-700 mb-2">Effectif</label>
                    <input type="number" name="effectif" id="effectif" value="{{ old('effectif', $entreprise->effectif) }}" min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('effectif')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" id="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="active" {{ old('status', $entreprise->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $entreprise->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $entreprise->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo (laissez vide pour conserver l'actuel)</label>
                    <input type="file" name="logo" id="logo" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('logo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($entreprise->logo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $entreprise->logo) }}" alt="Logo actuel" class="h-16 w-16 object-cover rounded">
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.entreprises.index') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-neutral-100 text-neutral-800 hover:bg-neutral-200">Annuler</a>
                <button type="submit" class="inline-flex items-center px-3 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700">Mettre à jour</button>
            </div>
        </form>
        </x-card>
    </div>
</div>
@endsection