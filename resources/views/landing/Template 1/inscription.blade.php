{{-- resources/views/templates/corporate/inscription.blade.php --}}
@extends('landing.Template 1.layouts.app')

@section('title', 'Inscription - ' . $evenement->titre)

@section('content')
<section class="min-h-screen pt-32 pb-20 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            
            <!-- Event Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 mb-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                    <i class="fas fa-calendar-check text-purple-600 text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Inscription</h1>
                <p class="text-slate-500 mb-6">Complétez le formulaire pour réserver votre place</p>
                
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl p-6">
                    <h2 class="text-xl font-bold mb-2">{{ $evenement->titre }}</h2>
                    <div class="flex flex-wrap justify-center gap-6 text-sm">
                        <span><i class="far fa-calendar mr-2"></i>{{ $evenement->date_heure_debut?->format('d/m/Y') ?? '-' }}</span>
                        <span><i class="far fa-clock mr-2"></i>{{ $evenement->date_heure_debut?->format('H:i') ?? '-' }}</span>
                        <span><i class="fas fa-map-marker-alt mr-2"></i>{{ $evenement->lieu ?? 'Lieu à définir' }}</span>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                        <ul class="list-disc list-inside text-red-700 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('inscription.store', $evenement) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Prénom</label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}" required
                                   class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nom</label>
                            <input type="text" name="nom" value="{{ old('nom') }}" required
                                   class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Téléphone</label>
                            <input type="tel" name="telephone" value="{{ old('telephone') }}" required
                                   class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Mot de passe</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Entreprise / Organisation</label>
                        <input type="text" name="company" value="{{ old('company') }}"
                               class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition">
                    </div>

                    @if($evenement->ateliers->count() > 0)
                    <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
                        <h3 class="font-bold text-slate-900 mb-4">Ateliers disponibles</h3>
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            @foreach($evenement->ateliers as $atelier)
                            @php
                                $registeredCount = $atelierCapacities[$atelier->id_atelier] ?? 0;
                                $isFull = $atelier->capacite && $registeredCount >= $atelier->capacite;
                            @endphp
                            <label class="flex items-center p-4 bg-white rounded-lg border {{ $isFull ? 'border-red-200 opacity-50' : 'border-slate-200 hover:border-purple-300' }} cursor-pointer transition">
                                <input type="checkbox" name="ateliers[]" value="{{ $atelier->id_atelier }}" 
                                       {{ $isFull ? 'disabled' : '' }}
                                       class="w-5 h-5 text-purple-600 rounded border-slate-300 focus:ring-purple-500">
                                <div class="ml-3 flex-1">
                                    <p class="font-medium text-slate-900">{{ $atelier->titre }}</p>
                                    <p class="text-sm text-slate-500">{{ $atelier->heure_debut?->format('H:i') }} - {{ $atelier->heure_fin?->format('H:i') }}</p>
                                </div>
                                @if($atelier->capacite)
                                    <span class="text-sm {{ $isFull ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $registeredCount }}/{{ $atelier->capacite }}
                                    </span>
                                @endif
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="bg-purple-50 rounded-xl p-6 border border-purple-200">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" name="one_to_one" value="1" {{ old('one_to_one') ? 'checked' : '' }}
                                   class="w-5 h-5 text-purple-600 rounded border-purple-300 focus:ring-purple-500 mt-1">
                            <div class="ml-3">
                                <span class="font-medium text-slate-900 block">Session one-to-one</span>
                                <span class="text-sm text-slate-600">Je souhaite participer à une session personnalisée avec un expert</span>
                            </div>
                        </label>
                    </div>

                    <div id="advanced-fields" class="space-y-4 {{ old('one_to_one') ? '' : 'hidden' }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Photo</label>
                                <input type="file" name="photo" accept="image/*"
                                       class="w-full px-4 py-3 rounded-lg border border-slate-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Poste</label>
                                <input type="text" name="poste" value="{{ old('poste') }}"
                                       class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">LinkedIn</label>
                            <input type="url" name="lien_linkedin" value="{{ old('lien_linkedin') }}"
                                   class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Présentation</label>
                            <textarea name="presentation" rows="3"
                                      class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none transition">{{ old('presentation') }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <input type="checkbox" id="terms" required class="w-5 h-5 text-purple-600 rounded border-slate-300 focus:ring-purple-500 mt-1">
                        <label for="terms" class="ml-3 text-sm text-slate-600">
                            J'accepte les <a href="#" class="text-purple-600 hover:underline">conditions générales</a> et la politique de confidentialité
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-4 rounded-xl font-bold text-lg hover:shadow-lg hover:shadow-purple-500/30 transition transform hover:-translate-y-0.5">
                        Confirmer mon inscription
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    document.querySelector('input[name="one_to_one"]')?.addEventListener('change', function() {
        document.getElementById('advanced-fields').classList.toggle('hidden', !this.checked);
    });
</script>
@endsection
