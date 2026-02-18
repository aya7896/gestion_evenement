@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto">
        <x-card>
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Modifier l'Événement</h1>

        <form action="{{ route('evenements.update', $evenement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">Titre de l'événement</label>
                    <input type="text" name="titre" id="titre" value="{{ old('titre', $evenement->titre) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('titre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type d'événement</label>
                    <select name="type" id="type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Sélectionner un type</option>
                        <option value="conférence" {{ old('type', $evenement->type) == 'conférence' ? 'selected' : '' }}>Conférence</option>
                        <option value="workshop" {{ old('type', $evenement->type) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="séminaire" {{ old('type', $evenement->type) == 'séminaire' ? 'selected' : '' }}>Séminaire</option>
                        <option value="formation" {{ old('type', $evenement->type) == 'formation' ? 'selected' : '' }}>Formation</option>
                        <option value="autre" {{ old('type', $evenement->type) == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="capacite" class="block text-sm font-medium text-gray-700 mb-2">Capacité maximale</label>
                    <input type="number" name="capacite" id="capacite" value="{{ old('capacite', $evenement->capacite) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('capacite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mode" class="block text-sm font-medium text-gray-700 mb-2">Mode</label>
                    <select name="mode" id="mode" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="présentiel" {{ old('mode', $evenement->mode) == 'présentiel' ? 'selected' : '' }}>Présentiel</option>
                        <option value="en ligne" {{ old('mode', $evenement->mode) == 'en ligne' ? 'selected' : '' }}>En ligne</option>
                        <option value="hybride" {{ old('mode', $evenement->mode) == 'hybride' ? 'selected' : '' }}>Hybride</option>
                    </select>
                    @error('mode')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="localisation" class="block text-sm font-medium text-gray-700 mb-2">Localisation</label>
                    <input type="text" name="localisation" id="localisation" value="{{ old('localisation', $evenement->localisation) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('localisation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="lieu" class="block text-sm font-medium text-gray-700 mb-2">Lieu</label>
                    <input type="text" name="lieu" id="lieu" value="{{ old('lieu', $evenement->lieu) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('lieu')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_heure_debut" class="block text-sm font-medium text-gray-700 mb-2">Date et heure de début</label>
                    <input type="datetime-local" name="date_heure_debut" id="date_heure_debut" 
                           value="{{ old('date_heure_debut', \Illuminate\Support\Carbon::parse($evenement->date_heure_debut)->format('Y-m-d\\TH:i')) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('date_heure_debut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_heure_fin" class="block text-sm font-medium text-gray-700 mb-2">Date et heure de fin</label>
                    <input type="datetime-local" name="date_heure_fin" id="date_heure_fin" 
                           value="{{ old('date_heure_fin', \Illuminate\Support\Carbon::parse($evenement->date_heure_fin)->format('Y-m-d\\TH:i')) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('date_heure_fin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $evenement->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sponsors associés</label>
                    @php
                        $selectedSponsors = old('partenaires', $evenement->partenaires->pluck('id_partenaire')->toArray());
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @forelse(($partenaires ?? collect()) as $partenaire)
                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-indigo-400 transition">
                                <input type="checkbox"
                                       name="partenaires[]"
                                       value="{{ $partenaire->id_partenaire }}"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       {{ in_array($partenaire->id_partenaire, $selectedSponsors) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-800">
                                    {{ $partenaire->nom }} <span class="text-gray-500">({{ strtoupper($partenaire->type) }})</span>
                                </span>
                            </label>
                        @empty
                            <p class="text-sm text-gray-500">Aucun sponsor actif disponible.</p>
                        @endforelse
                    </div>
                    @error('partenaires')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('partenaires.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="event_link" class="block text-sm font-medium text-gray-700 mb-2">Lien de l'événement (si en ligne)</label>
                    <input type="url" name="event_link" id="event_link" value="{{ old('event_link', $evenement->event_link) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('event_link')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="visibility" class="block text-sm font-medium text-gray-700 mb-2">Visibilité</label>
                    <select name="visibility" id="visibility" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="public" {{ old('visibility', $evenement->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                        <option value="private" {{ old('visibility', $evenement->visibility) == 'private' ? 'selected' : '' }}>Privé</option>
                    </select>
                    @error('visibility')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" id="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="active" {{ old('status', $evenement->status) == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="inactive" {{ old('status', $evenement->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="color_template" class="block text-sm font-medium text-gray-700 mb-2">Template couleur landing</label>
                    <select name="color_template" id="color_template"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="violet" {{ old('color_template', $evenement->color_template ?? 'violet') == 'violet' ? 'selected' : '' }}>Violet (par défaut)</option>
                        <option value="ocean" {{ old('color_template', $evenement->color_template) == 'ocean' ? 'selected' : '' }}>Ocean</option>
                        <option value="sunset" {{ old('color_template', $evenement->color_template) == 'sunset' ? 'selected' : '' }}>Sunset</option>
                        <option value="forest" {{ old('color_template', $evenement->color_template) == 'forest' ? 'selected' : '' }}>Forest</option>
                        <option value="slate" {{ old('color_template', $evenement->color_template) == 'slate' ? 'selected' : '' }}>Slate</option>
                    </select>
                    @error('color_template')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hero_appearance" class="block text-sm font-medium text-gray-700 mb-2">Apparence hero landing</label>
                    <select name="hero_appearance" id="hero_appearance"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="glass_soft" {{ old('hero_appearance', $evenement->hero_appearance ?? 'glass_soft') == 'glass_soft' ? 'selected' : '' }}>Glass Soft</option>
                        <option value="glass_strong" {{ old('hero_appearance', $evenement->hero_appearance) == 'glass_strong' ? 'selected' : '' }}>Glass Strong</option>
                        <option value="clean" {{ old('hero_appearance', $evenement->hero_appearance) == 'clean' ? 'selected' : '' }}>Clean</option>
                        <option value="cinematic" {{ old('hero_appearance', $evenement->hero_appearance) == 'cinematic' ? 'selected' : '' }}>Cinematic</option>
                    </select>
                    @error('hero_appearance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="plaquette_pdf" class="block text-sm font-medium text-gray-700 mb-2">Plaquette PDF (laissez vide pour conserver l'actuel)</label>
                    <input type="file" name="plaquette_pdf" id="plaquette_pdf" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('plaquette_pdf')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($evenement->plaquette_pdf)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $evenement->plaquette_pdf) }}" target="_blank" class="text-blue-500 hover:text-blue-700">
                                Voir la plaquette actuelle
                            </a>
                        </div>
                    @endif
                </div>

                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image de couverture (laissez vide pour conserver l'actuelle)</label>
                    <input type="file" name="image" id="image" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($evenement->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $evenement->image) }}" alt="Image actuelle" class="h-32 w-48 object-cover rounded">
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('evenements.index') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-neutral-100 text-neutral-800 hover:bg-neutral-200">Annuler</a>
                <button type="submit" class="inline-flex items-center px-3 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700">Mettre à jour</button>
            </div>
        </form>
        </x-card>
    </div>
</div>
@endsection



