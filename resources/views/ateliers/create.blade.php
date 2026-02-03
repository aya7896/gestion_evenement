@extends('layouts.app')

@section('content')
    <div class="container-custom">
        <div class="max-w-2xl mx-auto">
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Créer un Atelier</h1>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">Ajoutez un atelier à un événement</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i>Nouvel atelier
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900 dark:text-blue-300">Information</p>
                                <p class="text-sm text-blue-700 dark:text-blue-400">Les ateliers sont associés à des événements spécifiques. Assurez-vous de sélectionner le bon événement.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('ateliers.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Événement associé -->
                        <div class="form-group">
                            <label for="id_event" class="form-label">
                                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>Événement associé
                                <span class="text-red-500">*</span>
                            </label>
                            <select name="id_event" id="id_event" 
                                    class="input @error('id_event') input-error @enderror"
                                    required>
                                <option value="">Sélectionner un événement</option>
                                @foreach($evenements as $evenement)
                                    <option value="{{ $evenement->id_event }}" 
                                            {{ (old('id_event') == $evenement->id_event) || (isset($selectedEvenement) && $selectedEvenement->id_event == $evenement->id_event) ? 'selected' : '' }}>
                                        {{ $evenement->titre }} - {{ \Illuminate\Support\Carbon::parse($evenement->date_heure_debut)->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="form-help">Sélectionnez l'événement auquel associer cet atelier</p>
                            @error('id_event')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Informations de l'atelier -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="titre" class="form-label">
                                    <i class="fas fa-heading mr-2 text-blue-600"></i>Titre de l'atelier
                                </label>
                                <input type="text" name="titre" id="titre" value="{{ old('titre') }}" 
                                       class="input @error('titre') input-error @enderror"
                                       placeholder="Titre de l'atelier">
                                @error('titre')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="capacite" class="form-label">
                                    <i class="fas fa-users mr-2 text-blue-600"></i>Capacité maximale
                                </label>
                                <input type="number" name="capacite" id="capacite" value="{{ old('capacite') }}" 
                                       class="input @error('capacite') input-error @enderror"
                                       placeholder="Nombre de participants">
                                @error('capacite')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dates et heures -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="date" class="form-label">
                                    <i class="fas fa-calendar-day mr-2 text-blue-600"></i>Date de l'atelier
                                </label>
                                <input type="date" name="date" id="date" value="{{ old('date') }}" 
                                       class="input @error('date') input-error @enderror">
                                <p class="form-help">Doit être comprise entre les dates de l'événement</p>
                                @error('date')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="heure_debut" class="form-label">
                                    <i class="fas fa-clock mr-2 text-blue-600"></i>Heure de début
                                </label>
                                <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut') }}" 
                                       class="input @error('heure_debut') input-error @enderror">
                                @error('heure_debut')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="heure_fin" class="form-label">
                                    <i class="fas fa-clock mr-2 text-blue-600"></i>Heure de fin
                                </label>
                                <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin') }}" 
                                       class="input @error('heure_fin') input-error @enderror">
                                @error('heure_fin')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left mr-2 text-blue-600"></i>Description
                            </label>
                            <textarea name="description" id="description" rows="4" 
                                      class="input @error('description') input-error @enderror"
                                      placeholder="Description de l'atelier...">{{ old('description') }}</textarea>
                            <p class="form-help">Détaillez le contenu et les objectifs de l'atelier</p>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-neutral-100 dark:border-neutral-700">
                            <a href="{{ route('ateliers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Retour aux ateliers
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Ajouter l'atelier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection