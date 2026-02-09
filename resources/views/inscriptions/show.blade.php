@extends('layouts.app')

@section('title', 'Détails de l\'inscription - ' . $inscription->user->name)

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('evenements.show', $inscription->evenements->first()) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Retour à l'événement
            </a>
        </div>

        <!-- Header card -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Photo -->
                <div class="flex-shrink-0">
                    @if($inscription->photo)
                        @php
                            $img = $inscription->photo ?? null;
                            $imgNorm = $img ? preg_replace('#^(/)?(storage/|public/|storage/app/public/)#', '', $img) : null;
                            if ($imgNorm && \Illuminate\Support\Facades\Storage::disk('public')->exists($imgNorm)) {
                                $imgUrl = asset('storage/' . $imgNorm);
                            } elseif ($img && file_exists($img)) {
                                $imgUrl = asset($img);
                            } else {
                                $imgUrl = null;
                            }
                        @endphp
                        @if($imgUrl)
                        <img src="{{ $imgUrl }}" 
                             alt="{{ $inscription->user->name }}"
                             class="w-48 h-48 rounded-lg object-cover shadow-md">
                        @else
                        <div class="w-48 h-48 bg-gray-300 rounded-lg flex items-center justify-center shadow-md">
                            <i class="fas fa-user text-gray-500 text-6xl"></i>
                        </div>
                        @endif
                    @endif
                </div>

                <!-- Infos principales -->
                <div class="flex-1">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $inscription->user->name }}</h1>
                    
                    <div class="space-y-4">
                        <!-- Email -->
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-600 w-6"></i>
                            <span class="text-gray-700">{{ $inscription->user->email }}</span>
                        </div>

                        <!-- Téléphone -->
                        @if($inscription->user->telephone)
                            <div class="flex items-center">
                                <i class="fas fa-phone text-blue-600 w-6"></i>
                                <span class="text-gray-700">{{ $inscription->user->telephone }}</span>
                            </div>
                        @endif

                        <!-- Entreprise -->
                        @if($inscription->company)
                            <div class="flex items-center">
                                <i class="fas fa-building text-blue-600 w-6"></i>
                                <span class="text-gray-700">{{ $inscription->company }}</span>
                            </div>
                        @endif

                        <!-- Poste -->
                        @if($inscription->poste)
                            <div class="flex items-center">
                                <i class="fas fa-briefcase text-blue-600 w-6"></i>
                                <span class="text-gray-700">{{ $inscription->poste }}</span>
                            </div>
                        @endif

                        <!-- Date d'inscription -->
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt text-blue-600 w-6"></i>
                            <span class="text-gray-700">Inscrit le {{ \Carbon\Carbon::parse($inscription->date_ins)->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails complémentaires -->
        @if($inscription->poste || $inscription->presentation || $inscription->lien_linkedin || $inscription->objectif)
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Informations complémentaires</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($inscription->poste)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600 uppercase">Poste</h3>
                            <p class="text-gray-900 mt-2">{{ $inscription->poste }}</p>
                        </div>
                    @endif

                    @if($inscription->lien_linkedin)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600 uppercase">LinkedIn</h3>
                            <a href="{{ $inscription->lien_linkedin }}" target="_blank" class="text-blue-600 hover:text-blue-800 mt-2 flex items-center">
                                <i class="fab fa-linkedin mr-2"></i>{{ $inscription->lien_linkedin }}
                            </a>
                        </div>
                    @endif

                    @if($inscription->presentation)
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-semibold text-gray-600 uppercase">Présentation</h3>
                            <p class="text-gray-900 mt-2 whitespace-pre-wrap">{{ $inscription->presentation }}</p>
                        </div>
                    @endif

                    @if($inscription->objectif)
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-semibold text-gray-600 uppercase">Objectif</h3>
                            <p class="text-gray-900 mt-2 whitespace-pre-wrap">{{ $inscription->objectif }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Événement et ateliers -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Événement -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>Événement
                </h2>
                
                @forelse($inscription->evenements as $evenement)
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $evenement->titre }}</h3>
                            <p class="text-gray-600 text-sm mt-1">{{ $evenement->description }}</p>
                        </div>

                        <div class="border-t pt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date début:</span>
                                <span class="text-gray-900 font-medium">{{ $evenement->date_heure_debut->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date fin:</span>
                                <span class="text-gray-900 font-medium">{{ $evenement->date_heure_fin->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Lieu:</span>
                                <span class="text-gray-900 font-medium">{{ $evenement->lieu }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Aucun événement associé</p>
                @endforelse
            </div>

            <!-- Ateliers inscrits -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-chalkboard text-blue-600 mr-2"></i>Ateliers inscrits ({{ $inscription->ateliers->count() }})
                </h2>
                
                @if($inscription->ateliers->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($inscription->ateliers as $atelier)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                                <h3 class="font-semibold text-gray-900">{{ $atelier->titre }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($atelier->sujet ?? $atelier->description, 100) }}</p>
                                @if($atelier->date && $atelier->heure_debut)
                                    <div class="text-xs text-gray-500 mt-2 flex gap-3">
                                        <span><i class="far fa-calendar mr-1"></i>{{ $atelier->date->format('d/m/Y') }}</span>
                                        <span><i class="far fa-clock mr-1"></i>{{ $atelier->heure_debut->format('H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Aucun atelier sélectionné</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection