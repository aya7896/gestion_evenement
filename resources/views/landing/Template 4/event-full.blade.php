@extends('landing.Template 4.layouts.app')

@section('title', $evenement->titre . ' - Complet')

@section('content')

<section class="py-20 gradient-bg-dark min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">

            <!-- Icône d'alerte -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-red-100 rounded-full mb-6">
                    <i class="fas fa-exclamation-circle text-red-600 text-5xl"></i>
                </div>
            </div>

            <!-- Titre et message principal -->
            <div class="scroll-reveal bg-white rounded-2xl shadow-2xl p-8 md:p-12 text-center mb-6">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Événement complet</h1>
                
                <div class="bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-xl p-6 mb-8">
                    <h2 class="text-2xl font-bold mb-3">{{ $evenement->titre }}</h2>
                    <p class="text-lg">Les inscriptions à cet événement ne sont plus disponibles.</p>
                </div>

                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg mb-8">
                    <p class="text-gray-700 text-lg mb-2">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        <strong>Capacité maximale atteinte</strong>
                    </p>
                    <p class="text-gray-600">
                        Malheureusement, le nombre maximum de participants a été atteint pour cet événement.
                    </p>
                </div>

                <!-- Détails de l'événement -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 text-left bg-gray-50 p-6 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-calendar text-red-500 text-xl mt-1 flex-shrink-0"></i>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Date</p>
                            <p class="text-gray-800">{{ $evenement->date_heure_debut?->format('d/m/Y H:i') ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-red-500 text-xl mt-1 flex-shrink-0"></i>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Lieu</p>
                            <p class="text-gray-800">{{ $evenement->lieu ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-users text-red-500 text-xl mt-1 flex-shrink-0"></i>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Capacité</p>
                            <p class="text-gray-800">{{ $evenement->capacite }} participants</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-file-alt text-red-500 text-xl mt-1 flex-shrink-0"></i>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Type</p>
                            <p class="text-gray-800">{{ ucfirst($evenement->type ?? 'Non spécifié') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Description de l'événement -->
                @if($evenement->description)
                    <div class="bg-white border border-gray-200 p-6 rounded-lg mb-8 text-left">
                        <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-align-left text-red-500 mr-2"></i>
                            À propos de cet événement
                        </h3>
                        <p class="text-gray-700 leading-relaxed">{{ $evenement->description }}</p>
                    </div>
                @endif

                <!-- Options alternatives -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-8">
                    <h3 class="font-bold text-blue-900 mb-3 flex items-center">
                        <i class="fas fa-lightbulb text-blue-600 mr-2"></i>
                        Que faire?
                    </h3>
                    <ul class="text-blue-800 space-y-2 text-left">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-chevron-right text-blue-600 mt-1 flex-shrink-0"></i>
                            <span>Consultez les autres événements disponibles</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-chevron-right text-blue-600 mt-1 flex-shrink-0"></i>
                            <span>Revenir à la page d'accueil pour découvrir d'autres opportunités</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-chevron-right text-blue-600 mt-1 flex-shrink-0"></i>
                            <span>Contactez l'organisateur pour une liste d'attente</span>
                        </li>
                    </ul>
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-lg transition duration-200 transform hover:scale-105">
                        <i class="fas fa-home"></i>
                        Retour à l'accueil
                    </a>
                    <a href="/e/{{ $evenement->slug }}" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold rounded-lg transition duration-200">
                        <i class="fas fa-info-circle"></i>
                        Détails de l'événement
                    </a>
                </div>
            </div>

            <!-- Message encourageant -->
            <div class="scroll-reveal bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-8 text-white text-center">
                <h3 class="text-xl font-bold mb-2 flex items-center justify-center gap-2">
                    <i class="fas fa-bell"></i>
                    Restez informé!
                </h3>
                <p class="mb-4">Consultez régulièrement notre site ou inscrivez-vous à notre newsletter pour être notifié des prochains événements.</p>
                <a href="/" class="inline-block bg-white text-blue-600 font-bold py-2 px-6 rounded-lg hover:bg-blue-50 transition">
                    Découvrir les autres événements
                </a>
            </div>

        </div>
    </div>
</section>

@endsection
