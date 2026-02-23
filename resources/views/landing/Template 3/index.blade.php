{{-- resources/views/templates/tech/index.blade.php --}}
@extends('landing.Template 3.layouts.app')

@section('content')
@php
    $landingContent = is_array($evenement->landing_content ?? null) ? $evenement->landing_content : [];
    $heroTitle = data_get($landingContent, 'hero_title', $evenement->titre);
    $heroSubtitle = data_get($landingContent, 'hero_subtitle', Str::limit($evenement->description, 160));
    $primaryCtaText = data_get($landingContent, 'primary_cta_text', 'INITIALIZE_REGISTRATION()');
    $secondaryCtaText = data_get($landingContent, 'secondary_cta_text', '$ cat agenda.json');
@endphp
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center bg-black overflow-hidden pt-20">
    <!-- Grid Background -->
    <div class="absolute inset-0 bg-[linear-gradient(rgba(0,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(0,255,255,0.03)_1px,transparent_1px)] bg-[size:50px_50px]"></div>
    
    <!-- Animated Orbs -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-cyan-500/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>

    <div class="relative container mx-auto px-4 py-20">
        <div class="max-w-5xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 border border-cyan-500/50 rounded bg-cyan-500/10 text-cyan-400 font-mono text-xs mb-6">
                        <span class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></span>
                        SYSTEM.INIT(EVENT_{{ $evenement->date_heure_debut?->format('Y') ?? '2024' }})
                    </div>
                    
                    <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-none text-white">
                        {{ $heroTitle }}
                    </h1>
                    
                    <p class="text-gray-400 text-lg mb-8 font-mono border-l-2 border-cyan-400 pl-4">
                        {{ $heroSubtitle }}
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('inscription.create', $evenement) }}" class="group bg-cyan-400 text-black px-8 py-4 font-bold font-mono hover:bg-cyan-300 transition flex items-center gap-2 shadow-[0_0_20px_rgba(34,211,238,0.3)]">
                            <i class="fas fa-rocket"></i>
                            {{ $primaryCtaText }}
                        </a>
                        <a href="#agenda" class="border border-purple-500 text-purple-400 px-8 py-4 font-mono hover:bg-purple-500/10 transition">
                            {{ $secondaryCtaText }}
                        </a>
                    </div>

                    <!-- Terminal Window -->
                    <div class="mt-8 bg-gray-900 border border-gray-700 rounded-lg p-4 font-mono text-sm max-w-md shadow-2xl">
                        <div class="flex gap-2 mb-3">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <div class="text-green-400">$ ./check_availability.sh</div>
                        <div class="text-gray-300 mt-1">> Scanning event capacity...</div>
                        <div class="text-gray-300">> Slots available: {{ $evenement->capacite ?? 'Unlimited' }}</div>
                        <div class="text-cyan-400 mt-1 animate-pulse">_</div>
                    </div>
                </div>

                <div class="relative">
                    <div class="aspect-square border border-cyan-500/30 bg-gradient-to-br from-cyan-500/5 to-purple-500/5 p-8 relative overflow-hidden shadow-[0_0_30px_rgba(34,211,238,0.1)]">
                        <div class="absolute inset-0 bg-[linear-gradient(rgba(0,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(0,255,255,0.05)_1px,transparent_1px)] bg-[size:20px_20px]"></div>
                        
                        <div class="relative h-full flex flex-col justify-center items-center text-center">
                            <div class="text-7xl font-bold text-cyan-400 mb-4 drop-shadow-[0_0_10px_rgba(34,211,238,0.5)] font-mono">
                                {{ $evenement->ateliers->count() }}
                            </div>
                            <div class="text-gray-500 uppercase tracking-widest text-sm mb-8 font-mono">Workshops</div>
                            
                            <div class="grid grid-cols-2 gap-4 w-full">
                                <div class="bg-black/50 border border-cyan-500/30 rounded p-4">
                                    <div class="text-3xl font-bold text-purple-400 font-mono">
                                        {{ $evenement->ateliers->flatMap(fn($a) => $a->speakers)->unique('id_speaker')->count() }}
                                    </div>
                                    <div class="text-xs text-gray-500 uppercase mt-1">Speakers</div>
                                </div>
                                <div class="bg-black/50 border border-green-500/30 rounded p-4">
                                    <div class="text-3xl font-bold text-green-400 font-mono">
                                        {{ $evenement->capacite ?? '∞' }}
                                    </div>
                                    <div class="text-xs text-gray-500 uppercase mt-1">Capacity</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-24 bg-gray-900 border-y border-cyan-500/20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center gap-4 mb-8">
                <span class="text-cyan-400 font-mono text-sm">$ cat about.txt</span>
                <div class="flex-1 h-[1px] bg-cyan-500/30"></div>
            </div>
            
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-8">
                {{ $evenement->titre }}<span class="text-cyan-400 animate-pulse">_</span>
            </h2>
            
            <p class="text-gray-400 text-lg leading-relaxed font-mono mb-8">
                {{ $evenement->description }}
            </p>

            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['icon' => 'fa-code', 'color' => 'cyan', 'title' => 'Coding', 'desc' => '48h non-stop'],
                    ['icon' => 'fa-robot', 'color' => 'purple', 'title' => 'AI/ML', 'desc' => 'Latest models'],
                    ['icon' => 'fa-network-wired', 'color' => 'green', 'title' => 'Network', 'desc' => 'Connect & share']
                ] as $item)
                <div class="border border-{{ $item['color'] }}-500/30 bg-black/50 p-6 rounded hover:border-{{ $item['color'] }}-500/60 transition group">
                    <i class="fas {{ $item['icon'] }} text-{{ $item['color'] }}-400 text-3xl mb-4 group-hover:scale-110 transition block"></i>
                    <h3 class="text-white font-bold mb-2 font-mono">{{ $item['title'] }}</h3>
                    <p class="text-gray-500 text-sm">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Speakers Section -->
<section id="speakers" class="py-24 bg-black">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-4 mb-12">
            <span class="text-purple-400 font-mono text-sm">$ ls speakers/</span>
            <div class="flex-1 h-[1px] bg-purple-500/30"></div>
        </div>

        @php
            $speakers = $evenement->ateliers->flatMap(fn($a) => $a->speakers)->unique('id_speaker')->take(6);
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($speakers as $speaker)
            <div class="group relative bg-gray-900 border border-gray-800 hover:border-cyan-500/50 transition p-6 rounded">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded bg-gradient-to-br from-cyan-500/20 to-purple-500/20 flex items-center justify-center border border-cyan-500/30">
                        @if($speaker->photo_url)
                            <img src="{{ $speaker->photo_url }}" alt="{{ $speaker->full_name }}" class="w-full h-full object-cover rounded">
                        @else
                            <i class="fas fa-user text-cyan-400 text-2xl"></i>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-white font-bold font-mono group-hover:text-cyan-400 transition">{{ $speaker->full_name }}</h3>
                        <p class="text-gray-500 text-sm">{{ $speaker->poste ?? 'Developer' }}</p>
                        <p class="text-purple-400 text-xs mt-1">{{ $speaker->company ?? 'Independent' }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-600 py-12 font-mono">
                // Speakers list loading...
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Agenda Section -->
<section id="agenda" class="py-24 bg-gray-900 border-y border-cyan-500/20">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-4 mb-12">
            <span class="text-green-400 font-mono text-sm">$ cat agenda.json</span>
            <div class="flex-1 h-[1px] bg-green-500/30"></div>
        </div>

        @php
            $ateliers = $evenement->ateliers->sortBy('heure_debut');
        @endphp

        <div class="max-w-4xl mx-auto space-y-4">
            @forelse($ateliers as $index => $atelier)
            <div class="flex gap-6 items-start group p-4 rounded hover:bg-white/5 transition border border-transparent hover:border-cyan-500/30">
                <div class="w-20 text-right flex-shrink-0 font-mono">
                    <span class="text-cyan-400 text-lg">{{ $atelier->heure_debut?->format('H:i') }}</span>
                </div>
                
                <div class="w-3 h-3 rounded-full border-2 border-cyan-500 bg-gray-900 flex-shrink-0 mt-2 group-hover:bg-cyan-500 transition shadow-[0_0_10px_rgba(34,211,238,0.3)]"></div>
                
                <div class="flex-1 pb-8 {{ !$loop->last ? 'border-b border-gray-800' : '' }}">
                    <h3 class="text-xl font-bold text-white font-mono mb-1 group-hover:text-cyan-400 transition">
                        {{ $atelier->titre }}
                    </h3>
                    @if($atelier->speakers->isNotEmpty())
                    <p class="text-gray-500 text-sm font-mono">
                        > Speaker: {{ $atelier->speakers->map(fn($s) => $s->full_name)->implode(', ') }}
                    </p>
                    @endif
                    @if($atelier->sujet)
                    <p class="text-gray-600 text-sm mt-2">{{ Str::limit($atelier->sujet, 100) }}</p>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center text-gray-600 py-12 font-mono">
                // No workshops scheduled yet
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Sponsors Section -->
@if($evenement->partenaires->isNotEmpty())
<section id="sponsors" class="py-24 bg-black">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-4 mb-12">
            <span class="text-yellow-400 font-mono text-sm">$ ls sponsors/</span>
            <div class="flex-1 h-[1px] bg-yellow-500/30"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center opacity-50 hover:opacity-100 transition duration-500">
            @foreach($evenement->partenaires as $partenaire)
            <div class="flex items-center justify-center p-4 border border-gray-800 hover:border-yellow-500/30 transition rounded bg-gray-900/50">
                @if($partenaire->logo_url)
                    <img src="{{ $partenaire->logo_url }}" alt="{{ $partenaire->nom }}" class="max-h-12 max-w-full grayscale hover:grayscale-0 transition">
                @else
                    <span class="text-gray-500 font-mono text-sm">{{ $partenaire->nom }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-24 bg-gradient-to-b from-gray-900 to-black border-t border-cyan-500/30">
    <div class="container mx-auto px-4 text-center">
        <div class="inline-block mb-8 px-4 py-2 border border-green-500/30 bg-green-500/10 rounded">
            <span class="text-green-400 font-mono text-sm">
                <i class="fas fa-check-circle mr-2"></i>
                REGISTRATION_STATUS: <span class="animate-pulse">OPEN</span>
            </span>
        </div>
        
        <h2 class="text-4xl md:text-6xl font-bold text-white mb-6 font-mono">
            READY_TO_<span class="text-cyan-400">HACK</span>()?
        </h2>
        
        <p class="text-gray-400 mb-12 max-w-2xl mx-auto font-mono">
            // Join {{ $evenement->capacite ?? 'unlimited' }} developers for an unforgettable experience
        </p>
        
        <a href="{{ route('inscription.create', $evenement) }}" class="inline-flex items-center gap-3 bg-cyan-400 text-black px-12 py-5 font-bold font-mono text-lg hover:bg-cyan-300 transition shadow-[0_0_30px_rgba(34,211,238,0.4)] hover:shadow-[0_0_50px_rgba(34,211,238,0.6)]">
            <i class="fas fa-terminal"></i>
            {{ $primaryCtaText }}
        </a>
    </div>
</section>
@endsection
