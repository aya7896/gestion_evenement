{{-- resources/views/templates/corporate/index.blade.php --}}
@extends('landing.Template 1.layouts.app')

@section('content')
@php
    $landingContent = is_array($evenement->landing_content ?? null) ? $evenement->landing_content : [];
    $heroTitle = data_get($landingContent, 'hero_title', $evenement->titre);
    $heroSubtitle = data_get($landingContent, 'hero_subtitle', Str::limit($evenement->description, 200));
    $primaryCtaText = data_get($landingContent, 'primary_cta_text', 'Reserver ma place');
    $secondaryCtaText = data_get($landingContent, 'secondary_cta_text', 'En savoir plus');
@endphp
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%239333ea\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    
    <div class="absolute top-20 left-10 w-72 h-72 bg-purple-500/30 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>

    <div class="relative container mx-auto px-4 py-20">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-sm text-white/90 mb-8 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                {{ $evenement->type ?? 'Ã‰vÃ©nement Professionnel' }} â€¢ {{ $evenement->date_heure_debut?->format('F Y') ?? 'Prochainement' }}
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                {{ $heroTitle }}
            </h1>
            
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                {{ $heroSubtitle }}
            </p>

            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <a href="{{ route('inscription.create', $evenement) }}" class="group bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:shadow-xl hover:shadow-purple-500/30 transition transform hover:-translate-y-1 flex items-center gap-2">
                    {{ $primaryCtaText }}
                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition"></i>
                </a>
                <a href="#details" class="px-8 py-4 rounded-full border border-white/30 text-white font-semibold hover:bg-white/10 transition backdrop-blur-sm">
                    {{ $secondaryCtaText }}
                </a>
            </div>

            @php
                $eventDate = $evenement->date_heure_debut;
                $now = now();
            @endphp
            
            @if($eventDate && $eventDate->isFuture())
            <div class="grid grid-cols-4 gap-4 max-w-2xl mx-auto" id="countdown">
                @foreach([['Days', 'days'], ['Hours', 'hours'], ['Minutes', 'minutes'], ['Seconds', 'seconds']] as $label)
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-4 border border-white/10">
                    <div class="text-3xl md:text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400" id="{{ $label[1] }}">00</div>
                    <div class="text-xs text-gray-400 uppercase tracking-wider mt-1">{{ $label[0] }}</div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>

<!-- About Section -->
<section id="details" class="py-24 bg-slate-50">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-purple-600 font-semibold tracking-wider uppercase text-sm">Ã€ propos de l'Ã©vÃ©nement</span>
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mt-4 mb-6">
                    Une expÃ©rience <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-purple-600">unique</span>
                </h2>
                <p class="text-slate-600 text-lg leading-relaxed mb-6">
                    {{ $evenement->description }}
                </p>
                
                <div class="grid grid-cols-2 gap-6 mt-8">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Networking</h3>
                            <p class="text-sm text-slate-500">Rencontrez des professionnels</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-lightbulb text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Innovation</h3>
                            <p class="text-sm text-slate-500">DerniÃ¨res tendances</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('inscription.create', $evenement) }}" class="inline-flex items-center gap-2 mt-10 text-purple-600 font-semibold hover:text-purple-700 transition">
                    Je participe <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="relative">
                <div class="absolute -inset-4 bg-gradient-to-r from-purple-500 to-blue-500 rounded-3xl opacity-20 blur-2xl"></div>
                <img src="{{ $evenement->image ? Storage::url($evenement->image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800' }}" 
                     alt="{{ $evenement->titre }}" 
                     class="relative rounded-2xl shadow-2xl w-full object-cover h-[500px]">
            </div>
        </div>
    </div>
</section>

<!-- Speakers Section -->
<section id="speakers" class="py-24 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-purple-600 font-semibold tracking-wider uppercase text-sm">Experts</span>
            <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mt-4">Nos Speakers</h2>
        </div>

        @php
            $speakers = $evenement->ateliers->flatMap(fn($a) => $a->speakers)->unique('id_speaker')->take(6);
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($speakers as $speaker)
            <div class="group relative bg-slate-50 rounded-2xl overflow-hidden hover:shadow-xl transition duration-300">
                <div class="aspect-[4/5] overflow-hidden">
                    <img src="{{ $speaker->photo_url ?? 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400' }}" 
                         alt="{{ $speaker->full_name }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-6">
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $speaker->full_name }}</h3>
                        <p class="text-purple-400">{{ $speaker->poste ?? 'Expert' }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-slate-400 py-12">
                Les speakers seront annoncÃ©s prochainement
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Schedule Section -->
<section id="schedule" class="py-24 bg-slate-900 text-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-purple-400 font-semibold tracking-wider uppercase text-sm">Programme</span>
            <h2 class="text-4xl md:text-5xl font-bold mt-4">Planning de l'Ã©vÃ©nement</h2>
        </div>

        @php
            $ateliers = $evenement->ateliers->sortBy('heure_debut');
        @endphp

        <div class="max-w-4xl mx-auto space-y-4">
            @forelse($ateliers as $atelier)
            <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 flex flex-col md:flex-row items-center gap-6 border border-white/10 hover:border-purple-500/50 transition">
                <div class="md:w-32 text-center md:text-left">
                    <div class="text-2xl font-bold text-purple-400">
                        {{ $atelier->heure_debut?->format('H:i') ?? '--:--' }}
                    </div>
                    <div class="text-sm text-gray-400">
                        {{ $atelier->heure_fin?->format('H:i') ?? '--:--' }}
                    </div>
                </div>
                
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-xl font-bold mb-2">{{ $atelier->titre }}</h3>
                    <p class="text-gray-400 text-sm">
                        @if($atelier->speakers->isNotEmpty())
                            Par {{ $atelier->speakers->map(fn($s) => $s->full_name)->implode(', ') }}
                        @else
                            Atelier pratique
                        @endif
                    </p>
                </div>

                <a href="{{ route('inscription.create', $evenement) }}" class="px-6 py-3 bg-white/10 rounded-full text-sm font-semibold hover:bg-purple-500 transition">
                    S'inscrire
                </a>
            </div>
            @empty
            <div class="text-center text-gray-400 py-12">
                Le programme dÃ©taillÃ© sera publiÃ© prochainement
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Sponsors Section -->
<section id="sponsors" class="py-24 bg-slate-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-purple-600 font-semibold tracking-wider uppercase text-sm">Partenaires</span>
            <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mt-4">Nos Sponsors</h2>
        </div>

        @if($evenement->partenaires->isNotEmpty())
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center">
            @foreach($evenement->partenaires as $partenaire)
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition flex items-center justify-center h-32">
                @if($partenaire->logo_url)
                    <img src="{{ $partenaire->logo_url }}" alt="{{ $partenaire->nom }}" class="max-h-20 max-w-full object-contain">
                @else
                    <span class="text-slate-400 font-semibold">{{ $partenaire->nom }}</span>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center text-slate-400">
            Devenez le premier sponsor de cet Ã©vÃ©nement
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-gradient-to-r from-purple-600 to-blue-600 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
    
    <div class="container mx-auto px-4 text-center relative">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">PrÃªt Ã  rejoindre l'aventure ?</h2>
        <p class="text-xl text-white/80 mb-10 max-w-2xl mx-auto">
            Les places sont limitÃ©es. RÃ©servez votre billet dÃ¨s maintenant pour ne rien manquer.
        </p>
        <a href="{{ route('inscription.create', $evenement) }}" class="inline-flex items-center gap-3 bg-white text-purple-600 px-10 py-4 rounded-full font-bold text-lg hover:shadow-2xl transition transform hover:-translate-y-1">
            <i class="fas fa-ticket-alt"></i>
            {{ $primaryCtaText }}
        </a>
    </div>
</section>

@if($eventDate && $eventDate->isFuture())
<script>
    function setCountdownValues(days, hours, minutes, seconds) {
        const elDays = document.getElementById('days');
        const elHours = document.getElementById('hours');
        const elMinutes = document.getElementById('minutes');
        const elSeconds = document.getElementById('seconds');
        if (!elDays || !elHours || !elMinutes || !elSeconds) return false;

        elDays.textContent = String(days).padStart(2, '0');
        elHours.textContent = String(hours).padStart(2, '0');
        elMinutes.textContent = String(minutes).padStart(2, '0');
        elSeconds.textContent = String(seconds).padStart(2, '0');
        return true;
    }

    function updateCountdown() {
        const eventDate = new Date('{{ $eventDate->toIso8601String() }}');
        if (Number.isNaN(eventDate.getTime())) {
            setCountdownValues(0, 0, 0, 0);
            return false;
        }

        const now = new Date();
        const diff = eventDate - now;

        if (diff <= 0) {
            setCountdownValues(0, 0, 0, 0);
            return false;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        setCountdownValues(days, hours, minutes, seconds);
        return true;
    }

    let countdownInterval = null;
    if (updateCountdown()) {
        countdownInterval = setInterval(function () {
            if (!updateCountdown() && countdownInterval) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    }
</script>
@endif
@endsection

