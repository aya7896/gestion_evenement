@extends('landing.Template 4.layouts.app')

@section('title', 'Inscription confirmée')

@section('content')

<section class="py-20 gradient-bg-dark min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            
            <!-- Success Message -->
            <div class="scroll-reveal bg-white rounded-2xl shadow-2xl p-8 mb-6 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-6">
                    <i class="fas fa-check-circle text-green-500 text-5xl"></i>
                </div>
                
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    🎉 Inscription confirmée !
                </h1>
                
                <p class="text-lg text-gray-600 mb-6">
                    Félicitations {{ $inscription->user->prenom }}, vous êtes maintenant inscrit(e) à l'événement.
                </p>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 text-left">
                    <p class="text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Un email de confirmation a été envoyé à <strong>{{ $inscription->user->email }}</strong>
                    </p>
                </div>
            </div>

            <!-- Event Summary -->
            <div class="scroll-reveal bg-white rounded-2xl shadow-xl p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-clipboard-list text-pink-500 mr-3"></i>
                    Récapitulatif
                </h2>

                <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl p-6 mb-6">
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

                <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                    @if($inscription->photo)
                        <img src="{{ asset('storage/' . $inscription->photo) }}" 
                             alt="{{ $inscription->user->prenom }}"
                             class="w-20 h-20 rounded-full object-cover mr-6 border-4 border-pink-500/30">
                    @else
                        <div class="w-20 h-20 bg-gradient-to-br from-pink-400 to-purple-500 rounded-full flex items-center justify-center mr-6">
                            <i class="fas fa-user text-white text-3xl"></i>
                        </div>
                    @endif
                    <div>
                        <p class="text-xl font-bold text-gray-800">
                            {{ $inscription->user->prenom }} {{ $inscription->user->nom }}
                        </p>
                        <p class="text-gray-600">{{ $inscription->user->email }}</p>
                        <p class="text-gray-600">{{ $inscription->user->telephone }}</p>
                        @if($inscription->company)
                            <p class="text-pink-500 font-medium mt-1">
                                <i class="fas fa-briefcase mr-2"></i>
                                {{ $inscription->poste ?? 'Participant' }} chez {{ $inscription->company }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="scroll-reveal bg-white rounded-2xl shadow-xl p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-tasks text-pink-500 mr-3"></i>
                    Prochaines étapes
                </h2>

                <div class="space-y-4">
                    @foreach([
                        ['Vérifiez votre email', 'Vous recevrez un email de confirmation avec tous les détails'],
                        ['Préparez-vous', 'Consultez le programme et préparez vos questions'],
                        ['Rejoignez-nous', 'Présentez-vous 15 minutes avant le début']
                    ] as $index => $step)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 gradient-bg rounded-full flex items-center justify-center text-white font-bold mr-4">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $step[0] }}</h3>
                                <p class="text-gray-600 text-sm">{{ $step[1] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $landingEvent = $inscription->evenements->first();
                @endphp
                <a href="{{ $landingEvent ? route('public.evenement.landing', $landingEvent) : url('/') }}"
                   class="block gradient-bg text-white text-center py-4 rounded-full font-semibold hover:opacity-90 transition">
                    <i class="fas fa-eye mr-2"></i>
                    Voir l'événement
                </a>
                <button onclick="window.print()"
                        class="block bg-gray-700 text-white text-center py-4 rounded-full font-semibold hover:bg-gray-800 transition">
                    <i class="fas fa-print mr-2"></i>
                    Imprimer
                </button>
            </div>

            <!-- Cancel -->
            <div class="mt-8 text-center">
                <button onclick="confirmCancel()" 
                        class="text-red-400 hover:text-red-300 transition">
                    <i class="fas fa-times-circle mr-1"></i>
                    Annuler mon inscription
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    function confirmCancel() {
        if (confirm('Êtes-vous sûr de vouloir annuler votre inscription ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("inscription.cancel", $inscription->id_inscription) }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

@endsection
