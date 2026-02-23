{{-- resources/views/templates/corporate/confirmation.blade.php --}}
@extends('landing.Template 1.layouts.app')

@section('title', 'Inscription confirmée')

@section('content')
<section class="min-h-screen pt-32 pb-20 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            
            <div class="bg-white rounded-2xl shadow-2xl p-8 mb-6 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-6">
                    <i class="fas fa-check-circle text-green-500 text-5xl"></i>
                </div>
                
                <h1 class="text-4xl font-bold text-slate-900 mb-4">🎉 Inscription confirmée !</h1>
                
                <p class="text-lg text-slate-600 mb-6">
                    Félicitations {{ $inscription->user->prenom }}, vous êtes maintenant inscrit(e) à l'événement.
                </p>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 text-left rounded-lg">
                    <p class="text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Un email de confirmation a été envoyé à <strong>{{ $inscription->user->email }}</strong>
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                    <i class="fas fa-clipboard-list text-purple-500 mr-3"></i>
                    Récapitulatif
                </h2>

                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl p-6 mb-6">
                    <h3 class="text-xl font-bold mb-3">{{ $inscription->evenement->titre }}</h3>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center">
                            <i class="far fa-calendar mr-2"></i>
                            {{ $inscription->evenement->date_heure_debut->format('d/m/Y') }}
                        </div>
                        <div class="flex items-center">
                            <i class="far fa-clock mr-2"></i>
                            {{ $inscription->evenement->date_heure_debut->format('H:i') }}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $inscription->evenement->lieu }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-slate-50 rounded-xl">
                    @if($inscription->photo)
                        <img src="{{ asset('storage/' . $inscription->photo) }}" 
                             alt="{{ $inscription->user->prenom }}"
                             class="w-20 h-20 rounded-full object-cover mr-6 border-4 border-purple-200">
                    @else
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-blue-500 rounded-full flex items-center justify-center mr-6">
                            <i class="fas fa-user text-white text-3xl"></i>
                        </div>
                    @endif
                    <div>
                        <p class="text-xl font-bold text-slate-900">
                            {{ $inscription->user->prenom }} {{ $inscription->user->nom }}
                        </p>
                        <p class="text-slate-600">{{ $inscription->user->email }}</p>
                        <p class="text-slate-600">{{ $inscription->user->telephone }}</p>
                        @if($inscription->company)
                            <p class="text-purple-600 font-medium mt-1">
                                <i class="fas fa-briefcase mr-2"></i>
                                {{ $inscription->poste ?? 'Participant' }} chez {{ $inscription->company }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('public.evenement.landing', $inscription->evenement) }}"
                   class="block bg-gradient-to-r from-blue-500 to-purple-600 text-white text-center py-4 rounded-xl font-semibold hover:shadow-lg transition">
                    <i class="fas fa-eye mr-2"></i>
                    Voir l'événement
                </a>
                <button onclick="window.print()"
                        class="block bg-slate-700 text-white text-center py-4 rounded-xl font-semibold hover:bg-slate-800 transition">
                    <i class="fas fa-print mr-2"></i>
                    Imprimer
                </button>
            </div>

            <div class="mt-8 text-center">
                <button onclick="if(confirm('Êtes-vous sûr de vouloir annuler votre inscription ?')) { document.getElementById('cancel-form').submit(); }" 
                        class="text-red-400 hover:text-red-300 transition">
                    <i class="fas fa-times-circle mr-1"></i>
                    Annuler mon inscription
                </button>
                <form id="cancel-form" action="{{ route('inscription.cancel', $inscription) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
