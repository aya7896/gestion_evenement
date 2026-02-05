@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <!-- En-tête de l'événement -->
        <x-card>
            <!-- Image de l'événement -->
            <div class="relative">
                @php
                    $imagePublic = $evenement->image && file_exists(public_path('storage/' . $evenement->image));
                    $imageAbsolute = $evenement->image && file_exists($evenement->image);
                @endphp

                @if($imagePublic)
                    <div class="w-full h-80 overflow-hidden rounded-lg -mx-6 mt-0 bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                        <img src="{{ asset('storage/' . $evenement->image) }}" alt="{{ $evenement->titre }}" class="w-full h-full object-contain">
                    </div>
                @elseif($imageAbsolute)
                    <div class="w-full h-80 overflow-hidden rounded-lg -mx-6 mt-0 bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                        <img src="{{ $evenement->image }}" alt="{{ $evenement->titre }}" class="w-full h-full object-contain">
                    </div>
                @else
                    <div class="w-full h-80 bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center rounded-lg -mx-6 mt-0">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-500 text-lg">Aucune image disponible</span>
                        </div>
                    </div>
                @endif
                
                <!-- Badges d'état -->
                <div class="absolute top-4 right-4 flex space-x-2">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $evenement->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $evenement->status === 'active' ? 'Actif' : 'Inactif' }}
                    </span>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $evenement->validation_superAdmin ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <svg class="w-4 h-4 mr-1 {{ $evenement->validation_superAdmin ? 'text-green-500' : 'text-red-500' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $evenement->validation_superAdmin ? 'Validé' : 'Non validé' }}
                    </span>
                </div>
            </div>

            <!-- Informations principales -->
            <div class="mt-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">{{ $evenement->titre }}</h1>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ $evenement->entreprise->nom }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                {{ ucfirst($evenement->type) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Mode et visibilité -->
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-5 0-9-4-9-9s4-9 9-9"></path>
                            </svg>
                            {{ ucfirst($evenement->mode) }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $evenement->visibility === 'public' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ ucfirst($evenement->visibility) }}
                        </span>
                    </div>
                </div>

                <!-- Détails de l'événement -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Capacité</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $evenement->capacite }}</p>
                                <p class="text-xs text-gray-500">participants</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Durée</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ abs(\Illuminate\Support\Carbon::parse($evenement->date_heure_debut)->diffInDays(\Illuminate\Support\Carbon::parse($evenement->date_heure_fin))) }}
                                </p>
                                <p class="text-xs text-gray-500">jours</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Ateliers</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $evenement->ateliers->count() }}</p>
                                <p class="text-xs text-gray-500">au total</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Inscriptions</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $evenement->inscriptions->count() }}</p>
                                <p class="text-xs text-gray-500">participant(s)</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Participants</p>
                                <p class="text-2xl font-bold text-gray-900">{{ optional($evenement->inscriptions)->count() ?? 0 }}</p>
                                <p class="text-xs text-gray-500">inscrits</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dates et lieu -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Dates et horaires</h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-4 p-3 bg-blue-50 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">Début</p>
                                        <p class="text-sm text-gray-600">{{ \Illuminate\Support\Carbon::parse($evenement->date_heure_debut)->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 p-3 bg-green-50 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">Fin</p>
                                        <p class="text-sm text-gray-600">{{ \Illuminate\Support\Carbon::parse($evenement->date_heure_fin)->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Localisation</h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-4 p-3 bg-indigo-50 rounded-lg">
                                    <svg class="w-5 h-5 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4 4a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $evenement->lieu }}</p>
                                        <p class="text-sm text-gray-600">{{ $evenement->localisation }}</p>
                                    </div>
                                </div>
                                @if($evenement->event_link)
                                    <div class="flex items-center space-x-4 p-3 bg-purple-50 rounded-lg">
                                        <svg class="w-5 h-5 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-gray-900">Lien de l'événement</p>
                                            <a href="{{ $evenement->event_link }}" target="_blank" class="text-sm text-purple-600 hover:text-purple-800 underline">{{ $evenement->event_link }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Description -->
        <x-card>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Description de l'événement</h2>
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-700 leading-relaxed">{{ $evenement->description }}</p>
            </div>
        </x-card>

        <!-- Documents -->
@if($evenement->plaquette_pdf || $evenement->image)
                <x-card>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Documents et ressources</h2>
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Plaquette PDF</h3>
                                    <p class="text-sm text-gray-600">Téléchargez la documentation complète de l'événement</p>
                                </div>
                            </div>
                            <a href="{{ route('evenements.plaquette.download', $evenement) }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Télécharger la plaquette
                        </a>
                    </div>
                </div>
            </x-card>
        @endif

        <!-- Inscriptions -->
        <x-card>
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Inscriptions à l'événement</h2>
                    <p class="text-gray-600">{{ $evenement->inscriptions->count() }} personne(s) inscrite(s)</p>
                </div>
            </div>

            @if($evenement->inscriptions->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Aucune inscription pour cet événement</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Participant
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Entreprise
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Poste
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Date d'inscription
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($evenement->inscriptions as $inscription)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $inscription->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ $inscription->user->email ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ $inscription->company ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        {{ $inscription->poste ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                        @if($inscription->date_ins)
                                            {{ \Carbon\Carbon::parse($inscription->date_ins)->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            Voir détails
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>

        <!-- Ateliers -->
        <x-card>
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Ateliers associés</h2>
                    <p class="text-gray-600">Découvrez les différents ateliers proposés lors de cet événement</p>
                </div>
                @if(auth()->user()->collaborateurs()->first() && auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
                    <a href="{{ route('evenements.ateliers.create', $evenement) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ajouter un Atelier
                    </a>
                @endif
            </div>

            @if($evenement->ateliers->isEmpty())
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun atelier pour cet événement</h3>
                    <p class="text-gray-600">Cet événement ne comporte actuellement aucun atelier. Veuillez en ajouter un pour commencer.</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($evenement->ateliers as $atelier)
                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow bg-white">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $atelier->titre }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $atelier->sujet }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $atelier->status === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <svg class="w-4 h-4 mr-1 {{ $atelier->status === 'actif' ? 'text-green-500' : 'text-red-500' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ ucfirst($atelier->status) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Date</p>
                                        <p class="font-semibold text-gray-900">{{ $atelier->date->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Horaire</p>
                                        <p class="font-semibold text-gray-900">{{ $atelier->heure_debut }} - {{ $atelier->heure_fin }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3 p-3 bg-purple-50 rounded-lg">
                                    <svg class="w-5 h-5 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Capacité</p>
                                        <p class="font-semibold text-gray-900">{{ $atelier->capacite }} participants</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3 p-3 bg-orange-50 rounded-lg">
                                    <svg class="w-5 h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Participants</p>
                                        <p class="font-semibold text-gray-900">{{ optional($atelier->inscriptions)->count() ?? 0 }} inscrits</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('evenements.ateliers.show', [$evenement, $atelier]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Voir l'atelier
                                </a>
                                @if(auth()->user()->role === 'super_admin' || auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
                                    <a href="{{ route('evenements.ateliers.edit', [$evenement, $atelier]) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Modifier
                                    </a>
                                    <form action="{{ route('evenements.ateliers.destroy', [$evenement, $atelier]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet atelier ? Cette action est irréversible.')">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card>

        <!-- Actions -->
        <div class="mt-8 flex flex-col sm:flex-row sm:justify-end gap-3">
            @if(auth()->user()->collaborateurs()->first() && auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
                <a href="{{ route('evenements.edit', $evenement) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier l'événement
                </a>
                <form action="{{ route('evenements.destroy', $evenement) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-md" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible et supprimera également tous les ateliers associés.')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Supprimer l'événement
                    </button>
                </form>
            @endif
            <a href="{{ route('evenements.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection