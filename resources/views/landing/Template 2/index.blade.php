{{-- resources/views/templates/luxury/index.blade.php --}}
@extends('landing.Template 2.layouts.app')

@section('content')
@php
    $landingContent = is_array($evenement->landing_content ?? null) ? $evenement->landing_content : [];
    $heroTitle = data_get($landingContent, 'hero_title', $evenement->titre);
    $heroSubtitle = data_get($landingContent, 'hero_subtitle', Str::limit($evenement->description, 150));
    $primaryCtaText = data_get($landingContent, 'primary_cta_text', 'Confirmer ma presence');
    $secondaryCtaText = data_get($landingContent, 'secondary_cta_text', 'Decouvrir le programme');
@endphp
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center bg-black pt-24">
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1514525253440-b393452e8d26?w=1920&q=80')] bg-cover bg-center opacity-30"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black"></div>
    
    <div class="relative container mx-auto px-6 text-center">
        <div class="mb-8 flex items-center justify-center gap-4">
            <span class="w-16 h-[1px] bg-yellow-500"></span>
            <span class="text-yellow-500 uppercase tracking-[0.5em] text-sm font-serif">
                {{ $evenement->date_heure_debut?->format('d F Y') ?? 'Date Ã  venir' }}
            </span>
            <span class="w-16 h-[1px] bg-yellow-500"></span>
        </div>
        
        <h1 class="text-6xl md:text-8xl font-bold text-white mb-8 leading-none font-serif">
            {{ $heroTitle }}
        </h1>
        
        <p class="text-xl text-gray-300 mb-12 max-w-2xl mx-auto font-serif italic leading-relaxed">
            {{ $heroSubtitle }}
        </p>

        <div class="flex flex-col md:flex-row gap-6 justify-center items-center mb-16">
            <a href="{{ route('inscription.create', $evenement) }}" class="bg-yellow-500 text-black px-12 py-4 uppercase tracking-[0.3em] text-sm font-bold hover:bg-yellow-400 transition duration-300">
                {{ $primaryCtaText }}
            </a>
            <a href="#programme" class="border border-yellow-500 text-yellow-500 px-12 py-4 uppercase tracking-[0.3em] text-sm hover:bg-yellow-500 hover:text-black transition duration-300">
                {{ $secondaryCtaText }}
            </a>
        </div>

        <div class="grid grid-cols-3 gap-8 max-w-3xl mx-auto pt-8 border-t border-yellow-500/30">
            <div class="text-center">
                <div class="text-4xl font-light text-yellow-500 mb-2 font-serif">
                    {{ $evenement->ateliers->count() }}
                </div>
                <div class="text-xs text-gray-400 uppercase tracking-[0.3em]">ExpÃ©riences</div>
            </div>
            <div class="text-center border-x border-yellow-500/30">
                <div class="text-4xl font-light text-yellow-500 mb-2 font-serif">
                    {{ $evenement->capacite ?? '100' }}
                </div>
                <div class="text-xs text-gray-400 uppercase tracking-[0.3em]">InvitÃ©s</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-light text-yellow-500 mb-2 font-serif">
                    {{ $evenement->ateliers->flatMap(fn($a) => $a->speakers)->unique('id_speaker')->count() }}
                </div>
                <div class="text-xs text-gray-400 uppercase tracking-[0.3em]">PersonnalitÃ©s</div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="details" class="py-24 bg-neutral-950">
    <div class="container mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="order-2 lg:order-1">
                <img src="{{ $evenement->image ? Storage::url($evenement->image) : 'https://images.unsplash.com/photo-1566737236500-c8ac43014a67?w=800' }}" 
                     alt="{{ $evenement->titre }}" 
                     class="w-full h-[600px] object-cover border border-yellow-500/30">
            </div>
            
            <div class="order-1 lg:order-2">
                <span class="text-yellow-500 uppercase tracking-[0.5em] text-sm font-serif block mb-4">L'Ã©vÃ©nement</span>
                <h2 class="text-5xl font-bold text-white mb-8 font-serif leading-tight">
                    Une soirÃ©e <span class="italic text-yellow-500">d'exception</span>
                </h2>
                
                <div class="space-y-6 text-gray-400 font-serif leading-relaxed">
                    <p class="text-lg">{{ $evenement->description }}</p>
                    <p>Rejoignez-nous pour une soirÃ©e exclusive oÃ¹ le raffinement rencontre l'innovation. 
                    Un moment privilÃ©giÃ© de networking dans un cadre prestigieux.</p>
                </div>

                <div class="mt-10 pt-10 border-t border-yellow-500/30">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <span class="text-yellow-500 text-sm uppercase tracking-widest block mb-2">Date</span>
                            <span class="text-white font-serif text-lg">
                                {{ $evenement->date_heure_debut?->format('d F Y') ?? 'Ã€ venir' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-yellow-500 text-sm uppercase tracking-widest block mb-2">Lieu</span>
                            <span class="text-white font-serif text-lg">
                                {{ $evenement->lieu ?? 'Adresse prestigieuse' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Intervenants Section -->
<section id="intervenants" class="py-24 bg-black">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-yellow-500 uppercase tracking-[0.5em] text-sm font-serif block mb-4">TÃªtes d'affiche</span>
            <h2 class="text-5xl font-bold text-white font-serif">Nos Intervenants d'Exception</h2>
        </div>

        @php
            $speakers = $evenement->ateliers->flatMap(fn($a) => $a->speakers)->unique('id_speaker')->take(4);
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($speakers as $speaker)
            <div class="group relative">
                <div class="aspect-[3/4] bg-neutral-900 border border-yellow-500/20 group-hover:border-yellow-500/60 transition duration-500 overflow-hidden">
                    <img src="{{ $speaker->photo_url ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' }}" 
                         alt="{{ $speaker->full_name }}" 
                         class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-4 group-hover:translate-y-0 transition duration-500">
                        <h3 class="text-xl font-bold text-white font-serif mb-1">{{ $speaker->full_name }}</h3>
                        <p class="text-yellow-500 text-sm uppercase tracking-wider">{{ $speaker->poste ?? 'Expert' }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 py-12 font-serif italic">
                Les intervenants seront dÃ©voilÃ©s prochainement
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Programme Section -->
<section id="programme" class="py-24 bg-neutral-950">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-yellow-500 uppercase tracking-[0.5em] text-sm font-serif block mb-4">DÃ©roulement</span>
            <h2 class="text-5xl font-bold text-white font-serif">Le Programme</h2>
        </div>

        @php
            $ateliers = $evenement->ateliers->sortBy('heure_debut');
        @endphp

        <div class="max-w-4xl mx-auto space-y-0">
            @forelse($ateliers as $index => $atelier)
            <div class="flex gap-8 items-start group py-8 {{ !$loop->last ? 'border-b border-yellow-500/20' : '' }}">
                <div class="w-32 text-right flex-shrink-0">
                    <span class="text-3xl font-light text-yellow-500 font-serif">
                        {{ $atelier->heure_debut?->format('H:i') }}
                    </span>
                </div>
                
                <div class="w-4 h-4 rounded-full border-2 border-yellow-500 bg-black flex-shrink-0 mt-2 group-hover:bg-yellow-500 transition"></div>
                
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-white font-serif mb-2 group-hover:text-yellow-500 transition">
                        {{ $atelier->titre }}
                    </h3>
                    @if($atelier->speakers->isNotEmpty())
                    <p class="text-gray-500 font-serif italic">
                        Avec {{ $atelier->speakers->map(fn($s) => $s->full_name)->implode(', ') }}
                    </p>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center text-gray-500 py-12 font-serif italic">
                Le programme dÃ©taillÃ© sera communiquÃ© aux invitÃ©s
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Sponsors Section -->
@if($evenement->partenaires->isNotEmpty())
<section class="py-24 bg-black">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-yellow-500 uppercase tracking-[0.5em] text-sm font-serif block mb-4">Partenaires</span>
            <h2 class="text-4xl font-bold text-white font-serif">Ils nous font confiance</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-12 items-center opacity-60">
            @foreach($evenement->partenaires as $partenaire)
            <div class="flex items-center justify-center">
                @if($partenaire->logo_url)
                    <img src="{{ $partenaire->logo_url }}" alt="{{ $partenaire->nom }}" class="max-h-16 max-w-full grayscale hover:grayscale-0 transition duration-500">
                @else
                    <span class="text-gray-500 font-serif text-lg">{{ $partenaire->nom }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-24 bg-neutral-950 border-t border-yellow-500/30">
    <div class="container mx-auto px-6 text-center">
        <span class="text-yellow-500 uppercase tracking-[0.5em] text-sm font-serif block mb-6">RSVP</span>
        <h2 class="text-5xl md:text-6xl font-bold text-white mb-8 font-serif">
            RÃ©servez votre place
        </h2>
        <p class="text-gray-400 mb-12 max-w-2xl mx-auto font-serif text-lg">
            Les places sont strictement limitÃ©es pour garantir une expÃ©rience exclusive.
        </p>
        <a href="{{ route('inscription.create', $evenement) }}" class="inline-block bg-yellow-500 text-black px-16 py-5 uppercase tracking-[0.3em] text-sm font-bold hover:bg-yellow-400 transition duration-300">
            {{ $primaryCtaText }}
        </a>
    </div>
</section>
@endsection

