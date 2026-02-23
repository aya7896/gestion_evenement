@extends('landing.Template 4.layouts.app')

@section('title', 'Inscription - ' . $evenement->titre)

@section('content')

<section class="py-20 gradient-bg-dark min-h-screen">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">

            <div class="scroll-reveal bg-white rounded-2xl shadow-2xl p-8 mb-6 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-pink-100 rounded-full mb-5">
                    <i class="fas fa-user-plus text-pink-500 text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-3">Inscription a l'evenement</h1>
                <p class="text-gray-600 mb-5">Completez le formulaire pour confirmer votre participation.</p>

                <div class="bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl p-5">
                    <h2 class="text-xl font-bold mb-2">{{ $evenement->titre }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div class="flex items-center justify-center md:justify-start">
                            <i class="far fa-calendar mr-2"></i>
                            {{ $evenement->date_heure_debut?->format('d/m/Y H:i') ?? '-' }}
                        </div>
                        <div class="flex items-center justify-center md:justify-start">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $evenement->lieu ?? 'Lieu non renseigne' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="scroll-reveal bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-file-signature text-pink-500 mr-3"></i>
                    Formulaire d'inscription
                </h2>

                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl flex-shrink-0 mt-1"></i>
                            <div>
                                <h3 class="font-bold text-red-700 mb-2">Erreur d'inscription</h3>
                                <ul class="list-disc list-inside space-y-1 text-red-700">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 text-red-700 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 text-emerald-700 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-6 space-y-3">
                    <a href="{{ route('inscription.social.redirect', ['evenement' => $evenement, 'provider' => 'google']) }}"
                       class="w-full inline-flex items-center justify-center gap-3 px-4 py-3 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">
                        <i class="fab fa-google text-red-500"></i> Continuer avec Google
                    </a>
                    <a href="{{ route('inscription.social.redirect', ['evenement' => $evenement, 'provider' => 'facebook']) }}"
                       class="w-full inline-flex items-center justify-center gap-3 px-4 py-3 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">
                        <i class="fab fa-facebook-f text-blue-600"></i> Continuer avec Facebook
                    </a>
                </div>

                <form action="{{ route('inscription.store', $evenement) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @if(!empty($socialPrefill['provider']))
                        <input type="hidden" name="social_provider" value="{{ $socialPrefill['provider'] }}">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="prenom" value="{{ old('prenom', $socialPrefill['prenom'] ?? '') }}" placeholder="Prenom" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:border-pink-500 transition">
                        <input type="text" name="nom" value="{{ old('nom', $socialPrefill['nom'] ?? '') }}" placeholder="Nom" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:border-pink-500 transition">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <input type="email" name="email" value="{{ old('email', $socialPrefill['email'] ?? '') }}" placeholder="E-mail" required
                                   class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('email') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none {{ $errors->has('email') ? 'focus:border-red-500' : 'focus:border-pink-500' }} transition">
                            @if($errors->has('email'))
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $errors->first('email') }}
                                </p>
                            @endif
                        </div>
                        <input type="tel" name="telephone" value="{{ old('telephone') }}" placeholder="Telephone" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:border-pink-500 transition">
                    </div>

                    <input type="password" name="password" placeholder="{{ !empty($socialPrefill['provider']) ? 'Mot de passe (optionnel)' : 'Mot de passe' }}" {{ !empty($socialPrefill['provider']) ? '' : 'required' }}
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:border-pink-500 transition">

                    <input type="text" name="company" value="{{ old('company') }}" placeholder="Entreprise / Organisation"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:border-pink-500 transition">

                    @if($evenement->ateliers->count() > 0)
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Selectionnez vos ateliers</h3>
                            @if($errors->has('ateliers'))
                                <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-4 rounded text-red-700 text-sm">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>{{ $errors->first('ateliers') }}
                                </div>
                            @endif
                            <div class="space-y-3 max-h-56 overflow-y-auto">
                                @foreach($evenement->ateliers as $atelier)
                                    @php
                                        $registeredCount = $atelierCapacities[$atelier->id_atelier] ?? 0;
                                        $isFull = $atelier->capacite && $registeredCount >= $atelier->capacite;
                                    @endphp
                                    <label class="flex items-center p-3 bg-white rounded-lg border {{ $isFull ? 'border-red-300 opacity-60' : 'border-gray-200 hover:border-pink-300' }} cursor-pointer transition {{ $isFull ? 'cursor-not-allowed' : '' }}">
                                        <input type="checkbox" name="ateliers[]" value="{{ $atelier->id_atelier }}"
                                               {{ old('ateliers') && in_array($atelier->id_atelier, old('ateliers', [])) ? 'checked' : '' }}
                                               {{ $isFull ? 'disabled' : '' }}
                                               class="w-5 h-5 text-pink-500 rounded border-gray-300 focus:ring-pink-500">
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-gray-800 font-medium">{{ $atelier->titre }}</p>
                                                    <p class="text-gray-500 text-sm">{{ $atelier->heure_debut->format('H:i') }} - {{ $atelier->heure_fin->format('H:i') }}</p>
                                                </div>
                                                @if($atelier->capacite)
                                                    <div class="text-right ml-3">
                                                        <p class="text-sm font-semibold {{ $isFull ? 'text-red-600' : 'text-gray-600' }}">
                                                            {{ $registeredCount }}/{{ $atelier->capacite }}
                                                        </p>
                                                        @if($isFull)
                                                            <p class="text-xs text-red-600 flex items-center gap-1">
                                                                <i class="fas fa-exclamation-circle"></i> Complet
                                                            </p>
                                                        @else
                                                            <p class="text-xs text-green-600">
                                                                {{ $atelier->capacite - $registeredCount }} place{{ $atelier->capacite - $registeredCount > 1 ? 's' : '' }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" name="one_to_one" id="one_to_one" value="1" {{ old('one_to_one') ? 'checked' : '' }}
                                   class="w-5 h-5 text-pink-500 rounded border-gray-300 focus:ring-pink-500 mt-1">
                            <div class="ml-3">
                                <span class="text-gray-800 font-medium block">Je souhaite participer a une session one-to-one</span>
                                <span class="text-gray-500 text-sm">Vous serez contacte(e) pour fixer un rendez-vous personnalise</span>
                            </div>
                        </label>
                    </div>

                    <div id="advanced-fields" class="space-y-4 {{ old('one_to_one') ? '' : 'hidden' }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Photo</label>
                                <input type="file" name="photo" accept="image/*"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-500 file:text-white hover:file:bg-pink-600">
                            </div>
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Poste</label>
                                <input type="text" name="poste" value="{{ old('poste') }}"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:border-pink-500 transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">Lien LinkedIn</label>
                            <input type="url" name="lien_linkedin" value="{{ old('lien_linkedin') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:border-pink-500 transition">
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">Presentation</label>
                            <textarea name="presentation" rows="3"
                                      class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:border-pink-500 transition">{{ old('presentation') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-gray-600 text-sm mb-2">Objectif</label>
                            <textarea name="objectif" rows="2"
                                      class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:border-pink-500 transition">{{ old('objectif') }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-start pt-2">
                        <input type="checkbox" id="terms" required class="w-5 h-5 text-pink-500 rounded border-gray-300 focus:ring-pink-500 mt-1">
                        <label for="terms" class="ml-3 text-gray-600 text-sm">
                            J'accepte les <a href="#" class="text-pink-600 hover:underline">conditions generales</a>
                            et la <a href="#" class="text-pink-600 hover:underline">politique de confidentialite</a>
                        </label>
                    </div>

                    <button type="submit" class="w-full gradient-bg text-white py-4 rounded-full font-semibold text-lg flex items-center justify-center hover:opacity-90 transition">
                        <i class="fas fa-check-circle mr-2"></i>
                        Confirmer mon inscription
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

<script>
    const oneToOneCheckbox = document.getElementById('one_to_one');
    if (oneToOneCheckbox) {
        oneToOneCheckbox.addEventListener('change', function() {
            const fields = document.getElementById('advanced-fields');
            if (!fields) return;
            if (this.checked) {
                fields.classList.remove('hidden');
            } else {
                fields.classList.add('hidden');
            }
        });
    }
</script>

@endsection
