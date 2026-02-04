@extends('layouts.app')

@section('content')
    <div class="container-custom">
        <div class="max-w-4xl mx-auto">
            <!-- Atelier Header -->
            <div class="card mb-6">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg flex items-center justify-center">
                                    <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $atelier->titre }}</h1>
                                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Atelier de {{ $atelier->evenement->titre }}</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-3">
                                    <div class="font-medium text-neutral-900 dark:text-white">Date</div>
                                    <div class="text-neutral-600 dark:text-neutral-400">{{ $atelier->date->format('d/m/Y') }}</div>
                                </div>
                                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-3">
                                    <div class="font-medium text-neutral-900 dark:text-white">Heure</div>
                                    <div class="text-neutral-600 dark:text-neutral-400">{{ $atelier->heure_debut }} - {{ $atelier->heure_fin }}</div>
                                </div>
                                <div class="bg-neutral-50 dark:bg-neutral-800 rounded-lg p-3">
                                    <div class="font-medium text-neutral-900 dark:text-white">Capacité</div>
                                    <div class="text-neutral-600 dark:text-neutral-400">{{ $atelier->capacite }} participants</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="hidden md:block">
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i>Atelier
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($atelier->description)
                <div class="card mb-6">
                    <div class="card-header">
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Description</h2>
                    </div>
                    <div class="card-body">
                        <p class="text-neutral-700 dark:text-neutral-300 leading-relaxed">{{ $atelier->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Informations Événement -->
            <div class="card mb-6">
                <div class="card-header">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Informations sur l'événement</h2>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="font-medium text-neutral-900 dark:text-white mb-2">Événement</div>
                            <div class="text-neutral-600 dark:text-neutral-400">{{ $atelier->evenement->titre }}</div>
                        </div>
                        <div>
                            <div class="font-medium text-neutral-900 dark:text-white mb-2">Type</div>
                            <div class="text-neutral-600 dark:text-neutral-400">{{ ucfirst($atelier->evenement->type) }}</div>
                        </div>
                        <div>
                            <div class="font-medium text-neutral-900 dark:text-white mb-2">Mode</div>
                            <div class="text-neutral-600 dark:text-neutral-400">{{ ucfirst($atelier->evenement->mode) }}</div>
                        </div>
                        <div>
                            <div class="font-medium text-neutral-900 dark:text-white mb-2">Lieu</div>
                            <div class="text-neutral-600 dark:text-neutral-400">{{ $atelier->evenement->lieu }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('evenements.show', $atelier->evenement) }}" class="btn btn-primary flex-1">
                            <i class="fas fa-calendar-alt mr-2"></i>Voir l'événement
                        </a>
                        @if(auth()->user()->role === 'super_admin')
                            <a href="{{ route('evenements.ateliers.edit', [$atelier->evenement, $atelier]) }}" class="btn btn-secondary flex-1">
                                <i class="fas fa-edit mr-2"></i>Modifier
                            </a>
                            <form action="{{ route('evenements.ateliers.destroy', [$atelier->evenement, $atelier]) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-full" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet atelier ?')">
                                    <i class="fas fa-trash mr-2"></i>Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection