{{-- resources/views/templates/luxury/inscription.blade.php --}}
@extends('landing.Template 2.layouts.app')

@section('title', 'RSVP - ' . $evenement->titre)

@section('content')
<section class="relative min-h-screen pt-32 pb-20 bg-black overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_20%,rgba(234,179,8,0.12),transparent_35%),radial-gradient(circle_at_80%_10%,rgba(251,191,36,0.09),transparent_30%)]"></div>
    <div class="container mx-auto px-6 max-w-3xl relative">

        <div class="text-center mb-10">
            <span class="text-yellow-500 uppercase tracking-[0.45em] text-xs font-serif block mb-3">RSVP</span>
            <h1 class="text-4xl md:text-5xl font-bold text-white font-serif mb-3">Confirmer votre presence</h1>
            <div class="w-28 h-[1px] bg-gradient-to-r from-transparent via-yellow-500 to-transparent mx-auto"></div>
        </div>

        <div class="rounded-2xl border border-yellow-500/30 bg-neutral-950/90 backdrop-blur-sm shadow-[0_20px_60px_rgba(0,0,0,0.45)] p-6 md:p-10">
            <div class="mb-8 pb-6 border-b border-yellow-500/20">
                <h2 class="text-2xl font-bold text-white font-serif mb-2">{{ $evenement->titre }}</h2>
                <div class="flex flex-wrap gap-3 text-sm font-serif">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/30 text-yellow-400">
                        <i class="far fa-calendar mr-2"></i>{{ $evenement->date_heure_debut?->format('d F Y') ?? '-' }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/30 text-yellow-400">
                        <i class="far fa-clock mr-2"></i>{{ $evenement->date_heure_debut?->format('H:i') ?? '-' }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/30 text-yellow-400">
                        <i class="fas fa-map-marker-alt mr-2"></i>{{ $evenement->lieu ?? 'Lieu a confirmer' }}
                    </span>
                </div>
            </div>

            @if($errors->any())
                <div class="bg-red-900/20 border border-red-500/30 p-4 mb-6 rounded-xl">
                    <ul class="list-disc list-inside text-red-300 text-sm font-serif">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-900/20 border border-red-500/30 p-4 mb-6 rounded-xl text-red-300 text-sm font-serif">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-900/20 border border-green-500/30 p-4 mb-6 rounded-xl text-green-300 text-sm font-serif">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('inscription.store', $evenement) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @php
                    $prefill = is_array($socialPrefill ?? null) ? $socialPrefill : [];
                @endphp
                @if(!empty($prefill))
                    <input type="hidden" name="social_provider" value="{{ $prefill['provider'] ?? '' }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-yellow-500/80 text-xs uppercase tracking-[0.2em] mb-2 font-serif">Prenom *</label>
                        <input type="text" name="prenom" value="{{ old('prenom', $prefill['prenom'] ?? '') }}" required
                               class="w-full rounded-xl bg-black/60 border border-yellow-500/30 text-white px-4 py-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 outline-none transition font-serif">
                    </div>
                    <div>
                        <label class="block text-yellow-500/80 text-xs uppercase tracking-[0.2em] mb-2 font-serif">Nom *</label>
                        <input type="text" name="nom" value="{{ old('nom', $prefill['nom'] ?? '') }}" required
                               class="w-full rounded-xl bg-black/60 border border-yellow-500/30 text-white px-4 py-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 outline-none transition font-serif">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-yellow-500/80 text-xs uppercase tracking-[0.2em] mb-2 font-serif">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $prefill['email'] ?? '') }}" required
                               class="w-full rounded-xl bg-black/60 border border-yellow-500/30 text-white px-4 py-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 outline-none transition font-serif">
                    </div>
                    <div>
                        <label class="block text-yellow-500/80 text-xs uppercase tracking-[0.2em] mb-2 font-serif">Telephone *</label>
                        <input type="tel" name="telephone" value="{{ old('telephone') }}" required
                               class="w-full rounded-xl bg-black/60 border border-yellow-500/30 text-white px-4 py-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 outline-none transition font-serif">
                    </div>
                </div>

                <div>
                    <label class="block text-yellow-500/80 text-xs uppercase tracking-[0.2em] mb-2 font-serif">Mot de passe *</label>
                    <input type="password" name="password" required
                           class="w-full rounded-xl bg-black/60 border border-yellow-500/30 text-white px-4 py-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 outline-none transition font-serif">
                </div>

                <div>
                    <label class="block text-yellow-500/80 text-xs uppercase tracking-[0.2em] mb-2 font-serif">Entreprise</label>
                    <input type="text" name="company" value="{{ old('company') }}"
                           class="w-full rounded-xl bg-black/60 border border-yellow-500/30 text-white px-4 py-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 outline-none transition font-serif">
                </div>

                @if($evenement->ateliers->count() > 0)
                <div class="rounded-xl border border-yellow-500/20 p-5 bg-black/40">
                    <h3 class="text-yellow-500 uppercase tracking-[0.25em] text-xs font-serif mb-4">Ateliers</h3>
                    <div class="space-y-3 max-h-64 overflow-auto pr-1">
                        @foreach($evenement->ateliers as $atelier)
                        @php
                            $registeredCount = $atelierCapacities[$atelier->id_atelier] ?? 0;
                            $isFull = $atelier->capacite && $registeredCount >= $atelier->capacite;
                        @endphp
                        <label class="flex items-start gap-3 p-3 rounded-lg border border-yellow-500/10 hover:border-yellow-500/30 transition {{ $isFull ? 'opacity-50' : '' }}">
                            <input type="checkbox"
                                   name="ateliers[]"
                                   value="{{ $atelier->id_atelier }}"
                                   {{ old('ateliers') && in_array($atelier->id_atelier, old('ateliers', [])) ? 'checked' : '' }}
                                   {{ $isFull ? 'disabled' : '' }}
                                   class="mt-1 w-4 h-4 border-yellow-500/40 bg-black text-yellow-500 rounded focus:ring-yellow-500">
                            <div class="flex-1">
                                <div class="text-white font-serif">{{ $atelier->titre }}</div>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $atelier->heure_debut?->format('H:i') ?? '--:--' }} - {{ $atelier->heure_fin?->format('H:i') ?? '--:--' }}
                                </div>
                            </div>
                            @if($atelier->capacite)
                                <div class="text-xs {{ $isFull ? 'text-red-400' : 'text-yellow-500/70' }}">
                                    {{ $registeredCount }}/{{ $atelier->capacite }}
                                </div>
                            @endif
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="rounded-xl border border-yellow-500/20 p-5 bg-black/40">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="one_to_one" value="1" {{ old('one_to_one') ? 'checked' : '' }}
                               class="mt-1 w-4 h-4 border-yellow-500/40 bg-black text-yellow-500 rounded focus:ring-yellow-500">
                        <div>
                            <span class="text-white font-serif block">Session one-to-one</span>
                            <span class="text-gray-400 text-sm">Demandez un accompagnement personnalise.</span>
                        </div>
                    </label>
                </div>

                <div id="advanced-fields" class="space-y-4 {{ old('one_to_one') ? '' : 'hidden' }} border-t border-yellow-500/20 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-yellow-500/80 text-xs uppercase tracking-[0.2em] mb-2 font-serif">Photo</label>
                            <input type="file" name="photo" accept="image/*"
                                   class="w-full rounded-xl bg-black/60 border border-yellow-500/30 text-white px-4 py-3 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border file:border-yellow-500 file:bg-transparent file:text-yellow-500 file:text-sm file:font-serif hover:file:bg-yellow-500 hover:file:text-black transition">
                        </div>
                        <div>
                            <label class="block text-yellow-500/80 text-xs uppercase tracking-[0.2em] mb-2 font-serif">Fonction</label>
                            <input type="text" name="poste" value="{{ old('poste') }}"
                                   class="w-full rounded-xl bg-black/60 border border-yellow-500/30 text-white px-4 py-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 outline-none transition font-serif">
                        </div>
                    </div>
                    <div>
                        <label class="block text-yellow-500/80 text-xs uppercase tracking-[0.2em] mb-2 font-serif">LinkedIn</label>
                        <input type="url" name="lien_linkedin" value="{{ old('lien_linkedin') }}"
                               class="w-full rounded-xl bg-black/60 border border-yellow-500/30 text-white px-4 py-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 outline-none transition font-serif">
                    </div>
                    <div>
                        <label class="block text-yellow-500/80 text-xs uppercase tracking-[0.2em] mb-2 font-serif">Biographie</label>
                        <textarea name="presentation" rows="3"
                                  class="w-full rounded-xl bg-black/60 border border-yellow-500/30 text-white px-4 py-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 outline-none transition font-serif">{{ old('presentation') }}</textarea>
                    </div>
                </div>

                <div class="pt-6 border-t border-yellow-500/20">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" id="terms" required
                               class="mt-1 w-4 h-4 border-yellow-500/40 bg-black text-yellow-500 rounded focus:ring-yellow-500">
                        <span class="text-gray-400 text-sm font-serif">
                            J'accepte les conditions generales et la politique de confidentialite.*
                        </span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-yellow-500 to-amber-400 text-black py-4 uppercase tracking-[0.25em] text-sm font-bold hover:from-yellow-400 hover:to-amber-300 transition duration-300 mt-8 shadow-lg shadow-yellow-900/30">
                    Confirmer ma presence
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    document.querySelector('input[name="one_to_one"]')?.addEventListener('change', function () {
        const fields = document.getElementById('advanced-fields');
        if (fields) {
            fields.classList.toggle('hidden', !this.checked);
        }
    });
</script>
@endsection
