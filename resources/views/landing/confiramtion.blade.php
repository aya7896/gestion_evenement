@extends('landing.layouts.app')

@section('title', 'Inscription confirmée')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            
            <!-- Message de succès -->
            <div class="bg-white rounded-lg shadow-2xl p-8 mb-6 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
                    <i class="fas fa-check-circle text-green-600 text-4xl"></i>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-800 mb-4">
                    🎉 Inscription confirmée !
                </h1>
                
                <p class="text-lg text-gray-600 mb-6">
                    Félicitations {{ $inscription->user->prenom }}, vous êtes maintenant inscrit(e) à l'événement.
                </p>

                <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-6">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Un email de confirmation a été envoyé à <strong>{{ $inscription->user->email }}</strong>
                    </p>
                </div>
            </div>

            <!-- Récapitulatif de l'inscription -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-clipboard-list text-blue-600 mr-3"></i>
                    Récapitulatif de votre inscription
                </h2>

                <!-- Informations événement -->
                <div class="mb-6 pb-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Événement</h3>
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg p-4">
                        <h4 class="text-xl font-bold mb-2">{{ $inscription->evenement->titre }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                            <div>
                                <i class="far fa-calendar mr-2"></i>
                                {{ $inscription->evenement->date_heure_debut->format('d/m/Y') }}
                            </div>
                            <div>
                                <i class="far fa-clock mr-2"></i>
                                {{ $inscription->evenement->date_heure_debut->format('H:i') }}
                            </div>
                            <div>
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ $inscription->evenement->lieu }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations personnelles -->
                <div class="mb-6 pb-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Vos informations</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start">
                            @if($inscription->photo)
                                <img src="{{ asset('storage/' . $inscription->photo) }}" 
                                     alt="{{ $inscription->user->prenom }}"
                                     class="w-16 h-16 rounded-full object-cover mr-4">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-800">
                                    {{ $inscription->user->prenom }} {{ $inscription->user->nom }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $inscription->user->email }}</p>
                                <p class="text-sm text-gray-600">{{ $inscription->user->telephone }}</p>
                            </div>
                        </div>

                        @if($inscription->company || $inscription->poste)
                        <div>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-briefcase text-blue-600 mr-2"></i>
                                @if($inscription->poste)
                                    <strong>{{ $inscription->poste }}</strong>
                                @endif
                                @if($inscription->company)
                                    chez {{ $inscription->company }}
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>

                    @if($inscription->lien_linkedin)
                    <div class="mt-4">
                        <a href="{{ $inscription->lien_linkedin }}" target="_blank"
                           class="inline-flex items-center text-blue-600 hover:text-blue-700">
                            <i class="fab fa-linkedin mr-2"></i>
                            Voir le profil LinkedIn
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Date d'inscription -->
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <span>
                        <i class="far fa-calendar-check mr-2"></i>
                        Inscrit le {{ $inscription->date_ins->format('d/m/Y à H:i') }}
                    </span>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                        {{ ucfirst($inscription->status) }}
                    </span>
                </div>
            </div>

            <!-- Prochaines étapes -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-tasks text-blue-600 mr-3"></i>
                    Prochaines étapes
                </h2>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                            1
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Vérifiez votre email</h3>
                            <p class="text-sm text-gray-600">
                                Vous recevrez un email de confirmation avec tous les détails de l'événement.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                            2
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Préparez-vous</h3>
                            <p class="text-sm text-gray-600">
                                Consultez le programme et préparez vos questions pour profiter au maximum de l'événement.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                            3
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Rejoignez-nous</h3>
                            <p class="text-sm text-gray-600">
                                Le jour J, présentez-vous 15 minutes avant le début de l'événement.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('landing.show', $inscription->evenement->id_event) }}"
                   class="block bg-blue-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-eye mr-2"></i>
                    Voir l'événement
                </a>
                <button onclick="window.print()"
                        class="block bg-gray-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                    <i class="fas fa-print mr-2"></i>
                    Imprimer le récapitulatif
                </button>
            </div>

            <!-- Option annulation -->
            <div class="mt-6 text-center">
                <button onclick="confirmCancel()" 
                        class="text-red-600 hover:text-red-700 text-sm">
                    <i class="fas fa-times-circle mr-1"></i>
                    Annuler mon inscription
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    function confirmCancel() {
        if (confirm('Êtes-vous sûr de vouloir annuler votre inscription ?')) {
            // Soumettre le formulaire d'annulation
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