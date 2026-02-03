@extends('layouts.app')

@section('content')
    <div class="container-custom">
        <div class="max-w-2xl mx-auto">
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Modifier l'Atelier</h1>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mt-1">Mettez à jour les informations de l'atelier</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    <i class="fas fa-edit mr-2"></i>Modifier
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
                                <p class="text-sm text-blue-700 dark:text-blue-400">Cet atelier est associé à l'événement : <strong>{{ $atelier->evenement->titre }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('evenements.ateliers.update', [$atelier->evenement, $atelier]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Informations de l'atelier -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="titre" class="form-label">
                                    <i class="fas fa-heading mr-2 text-blue-600"></i>Titre de l'atelier
                                </label>
                                <input type="text" name="titre" id="titre" value="{{ old('titre', $atelier->titre) }}" 
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
                                <input type="number" name="capacite" id="capacite" value="{{ old('capacite', $atelier->capacite) }}" 
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
                                <input type="date" name="date" id="date" value="{{ old('date', \Illuminate\Support\Carbon::parse($atelier->date)->format('Y-m-d')) }}" 
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
                                <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut', $atelier->heure_debut) }}" 
                                       class="input @error('heure_debut') input-error @enderror">
                                @error('heure_debut')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="heure_fin" class="form-label">
                                    <i class="fas fa-clock mr-2 text-blue-600"></i>Heure de fin
                                </label>
                                <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin', $atelier->heure_fin) }}" 
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
                                      placeholder="Description de l'atelier...">{{ old('description', $atelier->description) }}</textarea>
                            <p class="form-help">Détaillez le contenu et les objectifs de l'atelier</p>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-neutral-100 dark:border-neutral-700">
                            <a href="{{ route('evenements.show', $atelier->evenement) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Retour à l'événement
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection