@extends('landing.layouts.app')

@section('title', 'Ateliers - ' . $evenement->titre)

@section('content')

<!-- En-tête de la page -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="mb-4">
            <a href="{{ route('public.evenement.landing', $evenement->id_event) }}" class="text-blue-100 hover:text-white transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour à l'événement
            </a>
        </div>
        <h1 class="text-4xl font-bold mb-4">Ateliers de l'événement</h1>
        <p class="text-xl text-blue-100">{{ $evenement->titre }}</p>
        <p class="text-blue-200 mt-2">{{ $evenement->ateliers->count() }} atelier(s) disponible(s)</p>
    </div>
</section>

<!-- Contenu principal -->
<div class="container mx-auto px-4 py-12">
    @if($evenement->ateliers->isEmpty())
        <!-- Message si aucun atelier -->
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Aucun atelier disponible</h2>
            <p class="text-gray-600 mb-6">Il n'y a actuellement aucun atelier programmé pour cet événement.</p>
            <a href="{{ route('public.evenement.landing', $evenement->id_event) }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour à l'événement
            </a>
        </div>
    @else
        <!-- Grille d'ateliers -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($evenement->ateliers as $atelier)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                    <!-- Image de l'atelier -->
                    <div class="relative bg-gradient-to-br from-purple-500 to-purple-600 h-48 flex items-center justify-center overflow-hidden">
                        @if($atelier->image)
                            <img src="{{ asset('storage/' . $atelier->image) }}" alt="{{ $atelier->titre }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-chalkboard text-white text-5xl mb-3"></i>
                                <span class="text-white text-sm font-semibold">Atelier</span>
                            </div>
                        @endif
                    </div>

                    <!-- Contenu de la carte -->
                    <div class="p-6">
                        <!-- Titre -->
                        <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">
                            {{ $atelier->titre }}
                        </h3>

                        <!-- Description -->
                        @if($atelier->sujet)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $atelier->sujet }}
                            </p>
                        @endif

                        <!-- Informations -->
                        <div class="space-y-3 mb-6 border-t border-gray-200 pt-4">
                            <!-- Date -->
                            <div class="flex items-start">
                                <i class="fas fa-calendar-alt text-purple-600 mt-1 mr-3 text-sm"></i>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">Date</p>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($atelier->date)->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <!-- Heure -->
                            <div class="flex items-start">
                                <i class="fas fa-clock text-purple-600 mt-1 mr-3 text-sm"></i>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">Horaire</p>
                                    <p class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($atelier->heure_debut)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($atelier->heure_fin)->format('H:i') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Capacité -->
                            <div class="flex items-start">
                                <i class="fas fa-users text-purple-600 mt-1 mr-3 text-sm"></i>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">Capacité</p>
                                    <p class="text-sm text-gray-600">{{ $atelier->capacite }} place(s)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton d'action -->
                        <a href="{{ route('inscription.create', $evenement->id_event) }}" class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                            <i class="fas fa-user-plus mr-2"></i>
                            S'inscrire
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Bouton retour -->
    <div class="mt-12 text-center">
        <a href="{{ route('public.evenement.landing', $evenement->id_event) }}" class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Retour à l'événement
        </a>
    </div>
</div>

@endsection
