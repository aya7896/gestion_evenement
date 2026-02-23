@extends('landing.Template 4.layouts.app')

@section('title', $evenement->titre)

@section('content')
@php
    $landingContent = is_array($evenement->landing_content ?? null) ? $evenement->landing_content : [];
    $heroTitle = data_get($landingContent, 'hero_title', $evenement->titre);
    $heroSubtitle = data_get($landingContent, 'hero_subtitle', Str::limit($evenement->description, 150));
    $primaryCtaText = data_get($landingContent, 'primary_cta_text', 'REGISTER NOW');
    $secondaryCtaText = data_get($landingContent, 'secondary_cta_text', 'VIEW MORE');
@endphp

<!-- Hero Section -->
<section id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0">
        @php
            $img = $evenement->image ?? null;
            $imgNorm = $img ? str_replace('\\', '/', $img) : null;
            $imgNorm = $imgNorm ? preg_replace('#^(/)?(storage/|public/|storage/app/public/)#', '', $imgNorm) : null;

            if ($imgNorm && \Illuminate\Support\Facades\Storage::disk('public')->exists($imgNorm)) {
                $imgUrl = \Illuminate\Support\Facades\Storage::url($imgNorm);
            } elseif ($img && filter_var($img, FILTER_VALIDATE_URL)) {
                $imgUrl = $img;
            } elseif ($img && file_exists($img)) {
                $imgUrl = asset($img);
            } else {
                $imgUrl = 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920';
            }

            $imgUrlAbs = $imgUrl;
            if ($imgUrlAbs && !preg_match('#^https?://#', $imgUrlAbs)) {
                $imgUrlAbs = url($imgUrlAbs);
            }

        @endphp
        @section('meta')
            <meta property="og:title" content="{{ $evenement->titre }}" />
            <meta property="og:description" content="{{ Str::limit($evenement->description, 160) }}" />
            <meta property="og:image" content="{{ $imgUrlAbs }}" />
            <meta property="og:url" content="{{ route('public.evenement.landing', $evenement) }}" />
            <meta property="og:type" content="website" />
            <meta name="twitter:card" content="summary_large_image" />
            <meta name="twitter:title" content="{{ $evenement->titre }}" />
            <meta name="twitter:description" content="{{ Str::limit($evenement->description, 160) }}" />
            <meta name="twitter:image" content="{{ $imgUrlAbs }}" />
        @endsection
           <div class="absolute inset-0 -z-10" aria-hidden="true">
              {{-- static CSS background as fallback / low-res placeholder --}}
              <div class="absolute inset-0 bg-center bg-cover bg-no-repeat" 
                  style="background-image: url('{{ $imgUrl }}'); background-position:center center; background-size:cover; background-repeat:no-repeat; opacity:1; transition: opacity .7s;"></div>

              {{-- real image element loads lazily and fades in for sharper result on all devices --}}
              <img src="{{ $imgUrl }}" 
                  alt="{{ $evenement->titre }}" 
                  loading="lazy"
                  class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-700"
                  onload="this.style.opacity=1; if(this.previousElementSibling) this.previousElementSibling.style.opacity=0;">
           </div>
           <div class="absolute inset-0 hero-gradient"></div>
           <div class="absolute inset-0 bg-black/35"></div>
        <div class="hero-orb w-64 h-64 md:w-80 md:h-80 bg-conf-secondary/60 top-20 -left-20"></div>
        <div class="hero-orb w-72 h-72 md:w-96 md:h-96 bg-conf-light/40 -bottom-16 right-0"></div>
    </div>

    <!-- Content -->
    <div class="relative container mx-auto px-4 pt-32 pb-20">
        <div class="max-w-4xl mx-auto text-center">
            <div class="scroll-reveal glass-panel accent-ring rounded-3xl p-6 md:p-10">
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                    {{ $heroTitle }}
                </h1>
                <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-2xl mx-auto">
                    {{ $heroSubtitle }}
                </p>
                
                <!-- Event Meta -->
                <div class="flex flex-wrap justify-center gap-6 mb-10 text-white/90">
                    <span class="stat-chip px-5 py-2 rounded-full flex items-center">
                        <i class="far fa-calendar mr-2 text-pink-400"></i>
                        {{ $evenement->date_heure_debut->format('F d, Y') }}
                    </span>
                    <span class="stat-chip px-5 py-2 rounded-full flex items-center">
                        <i class="far fa-user mr-2 text-pink-400"></i>
                        {{ $evenement->entreprise->nom ?? 'Entreprise Inconnue' }}
                    </span>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#details" class="btn-gradient text-white px-8 py-4 rounded-full font-semibold flex items-center">
                        {{ $secondaryCtaText }} <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                          <a href="{{ route('inscription.create', $evenement) }}" class="btn-outline-glow text-white px-8 py-4 rounded-full font-semibold transition flex items-center">
                              {{ $primaryCtaText }} <i class="fas fa-arrow-right ml-2"></i>
                          </a>
                </div>
    </div>
</section>

<!-- About Section -->
<section id="details" class="py-20 gradient-bg-dark">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="scroll-reveal">
                <span class="section-title font-semibold tracking-wider uppercase text-sm">About The {{ $evenement->type ?? ' Event' }}</span>
                <h2 class="text-4xl md:text-5xl font-bold text-white mt-4 mb-6">
                    Welcome to {{ $evenement->titre }}<br>
                    <span class="gradient-text">{{ $evenement->type ?? ' Event' }}</span>
                </h2>
                <p class="text-gray-300 leading-relaxed mb-6">
                    {{ $evenement->description }}
                </p>
                <p class="text-gray-400 leading-relaxed mb-8">
                    Join us for an immersive experience where industry leaders share insights, 
                    innovative strategies, and networking opportunities that will transform 
                    your professional journey.
                </p>
                <a href="{{ route('inscription.create', $evenement) }}" class="btn-gradient text-white px-8 py-4 rounded-full font-semibold inline-flex items-center">
                    INTERESTED <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="scroll-reveal relative">
                <div class="absolute -inset-4 gradient-bg rounded-2xl opacity-50 blur-2xl"></div>
                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600" 
                     alt="Speaker" 
                     class="relative rounded-2xl shadow-2xl w-full object-cover h-[500px] border border-white/20">
            </div>
        </div>
    </div>
</section>

<!-- Countdown Section -->
<section class="py-16 gradient-bg-dark border-y border-white/10">
    <div class="container mx-auto px-4 text-center">
        <h3 class="text-2xl font-bold text-white mb-8">Count Every Second Until the Event</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 max-w-3xl mx-auto" id="countdown">
            <div class="glass-panel rounded-lg p-6 accent-ring">
                <div class="text-4xl font-bold text-pink-400" id="months">00</div>
                <div class="text-gray-400 text-sm uppercase tracking-wider">Months</div>
            </div>
            <div class="glass-panel rounded-lg p-6 accent-ring">
                <div class="text-4xl font-bold text-pink-400" id="days">00</div>
                <div class="text-gray-400 text-sm uppercase tracking-wider">Days</div>
            </div>
            <div class="glass-panel rounded-lg p-6 accent-ring">
                <div class="text-4xl font-bold text-pink-400" id="hours">00</div>
                <div class="text-gray-400 text-sm uppercase tracking-wider">Hours</div>
            </div>
            <div class="glass-panel rounded-lg p-6 accent-ring">
                <div class="text-4xl font-bold text-pink-400" id="minutes">00</div>
                <div class="text-gray-400 text-sm uppercase tracking-wider">Minutes</div>
            </div>
            <div class="glass-panel rounded-lg p-6 accent-ring">
                <div class="text-4xl font-bold text-pink-400" id="seconds">00</div>
                <div class="text-gray-400 text-sm uppercase tracking-wider">Seconds</div>
            </div>
        </div>
    </div>
</section>

<!-- Speakers Section -->
<section id="speakers" class="py-20 relative overflow-hidden">
    <div class="absolute inset-0 gradient-bg-dark"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-pink-500/20 to-purple-600/20"></div>
    
    <div class="container mx-auto px-4 relative">
        <div class="text-center mb-16 scroll-reveal">
            <span class="section-title font-semibold tracking-wider uppercase text-sm">Our Speakings</span>
            <h2 class="text-4xl md:text-5xl font-bold text-white mt-4">Who's Speaking</h2>
        </div>

        @php
            $eventAteliers = $evenement->ateliers
                ->filter(fn ($atelier) => (int) $atelier->id_event === (int) $evenement->id_event)
                ->values();
            $eventSpeakers = $eventAteliers
                ->flatMap(fn ($atelier) => $atelier->speakers)
                ->unique('id_speaker')
                ->take(6);
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($eventSpeakers as $speaker)
                @php
                    $speakerImg = $speaker->photo_url ?: ('https://images.unsplash.com/photo-' . (1507003211169 + (int) $speaker->id_speaker) . '?w=400&h=500&fit=crop');
                @endphp
                <div class="scroll-reveal group relative overflow-hidden rounded-2xl card-hover border border-white/20 accent-ring">
                    <img src="{{ $speakerImg }}"
                         alt="{{ $speaker->full_name }}"
                         onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=900&fit=crop';"
                         class="w-full h-96 md:h-[26rem] object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-xl font-bold text-white">{{ $speaker->full_name }}</h3>
                        <p class="text-pink-300">{{ $speaker->poste ?: ($speaker->company ?: 'Guest speaker') }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-white/70">
                    Aucun speaker associe a cet evenement pour le moment.
                </div>
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('public.evenement.ateliers', $evenement) }}" class="inline-flex items-center px-8 py-4 btn-outline-glow rounded-full text-white transition">
                VIEW ALL WORKSHOPS <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Schedule Section -->
<section id="schedule" class="py-20 gradient-bg-dark">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 scroll-reveal">
            <span class="section-title font-semibold tracking-wider uppercase text-sm">Our Timetable</span>
            <h2 class="text-4xl md:text-5xl font-bold text-white mt-4">Schedule Plan</h2>
        </div>

        <!-- Tabs -->
        <div class="flex justify-center mb-12 scroll-reveal">
            <div class="glass-panel rounded-lg p-1 flex flex-wrap justify-center">
                @php
                    $debutEvent = \Carbon\Carbon::parse($evenement->date_heure_debut)->startOfDay();
                    $finEvent = \Carbon\Carbon::parse($evenement->date_heure_fin ?? $evenement->date_heure_debut)->startOfDay();
                    $ateliersTries = $eventAteliers->sortBy(fn ($atelier) => ($atelier->date?->format('Y-m-d') ?? $debutEvent->format('Y-m-d')) . ' ' . ($atelier->heure_debut?->format('H:i:s') ?? '00:00:00'));
                    $jours = [];
                    for ($dateCourante = $debutEvent->copy(); $dateCourante->lte($finEvent); $dateCourante->addDay()) {
                        $key = $dateCourante->format('Y-m-d');
                        $jours[$key] = [
                            'jour' => $dateCourante->copy()->locale('en')->isoFormat('dddd'),
                            'date' => $dateCourante->format('F d, Y'),
                        ];
                    }
                @endphp
                @foreach($jours as $key => $info)
                    <button onclick="switchTab('{{ $key }}')" id="tab-{{ $key }}" class="tab-btn px-6 py-3 rounded-lg text-white font-semibold transition {{ $loop->first ? 'bg-white/20' : 'text-white/70 hover:text-white' }}">
                        {{ strtoupper($info['jour']) }}<br><span class="text-sm font-normal opacity-70">{{ $info['date'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Schedule Content -->
        <div class="max-w-4xl mx-auto space-y-4" id="schedule-content">
            @forelse($ateliersTries as $atelier)
                <div class="schedule-item scroll-reveal glass-panel rounded-xl p-6 flex flex-col md:flex-row items-center gap-6 border border-white/20 hover:border-pink-500/50 transition" data-day="{{ $atelier->date?->format('Y-m-d') ?? $debutEvent->format('Y-m-d') }}">
                    @php
                        $mainSpeaker = $atelier->speakers->first();
                        $mainSpeakerImage = $mainSpeaker ? $mainSpeaker->photo_url : 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop';
                        $startTime = $atelier->heure_debut ? \Carbon\Carbon::parse($atelier->heure_debut)->format('H:i') : '--:--';
                        $endTime = $atelier->heure_fin ? \Carbon\Carbon::parse($atelier->heure_fin)->format('H:i') : '--:--';
                    @endphp
                    <img src="{{ $mainSpeakerImage }}"
                         alt="{{ $mainSpeaker?->full_name ?: 'Speaker' }}"
                         onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop';"
                         class="w-24 h-24 md:w-28 md:h-28 rounded-full object-cover border-4 border-pink-500/40 shadow-lg">
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-xl font-bold text-white mb-1">{{ $atelier->titre }}</h3>
                        <p class="text-gray-400">
                            by {{ $atelier->speakers->map(fn ($speaker) => $speaker->full_name)->filter()->implode(', ') ?: 'Expert' }}
                        </p>
                        <p class="text-pink-300 text-sm">
                            {{ $atelier->speakers->map(fn ($speaker) => $speaker->poste ?: ($speaker->company ?: 'Speaker'))->filter()->unique()->implode(' / ') ?: 'Speaker' }}
                        </p>
                    </div>
                    <div class="text-center md:text-right">
                        <div class="flex items-center text-pink-400 mb-1">
                            <i class="far fa-clock mr-2"></i>
                            <span>{{ $startTime }} - {{ $endTime }}</span>
                        </div>
                        <div class="flex items-center text-gray-400 text-sm">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{ $evenement->lieu }}</span>
                        </div>
                    </div>
                    <a href="{{ route('inscription.create', $evenement) }}" class="btn-gradient text-white px-6 py-3 rounded-full text-sm font-semibold">
                        {{ $secondaryCtaText }} <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @empty
            @endforelse
            <div id="schedule-empty" class="hidden text-center text-gray-300 py-8 bg-white/5 rounded-xl border border-white/10">
                No workshop planned for this day.
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('public.evenement.plaquette.download', $evenement) }}" class="inline-flex items-center px-8 py-4 border-2 border-pink-500 text-pink-400 rounded-full hover:bg-pink-500 hover:text-white transition">
                DOWNLOAD SCHEDULE (PDF)
            </a>
        </div>
    </div>
</section>


<!-- Sponsors -->
<section id="sponsors" class="py-16 gradient-bg-dark border-y border-white/10">
    <div class="container mx-auto px-4">
        <h3 class="text-center text-2xl font-bold text-white mb-12">Official Sponsors</h3>
        @php
            $sponsors = $evenement->partenaires;
        @endphp
        @if($sponsors->isNotEmpty())
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 items-stretch">
                @foreach($sponsors as $sponsor)
                    <div class="flex justify-center">
                        <div class="glass-panel border border-white/30 rounded-2xl px-4 py-4 w-full h-32 md:h-36 flex items-center justify-center hover:border-pink-400/60 transition shadow-md">
                            @if($sponsor->logo_url)
                                <img src="{{ $sponsor->logo_url }}"
                                     alt="{{ $sponsor->nom }}"
                                     onerror="this.onerror=null;this.style.display='none';this.parentNode.querySelector('.sponsor-fallback').style.display='block';"
                                     class="max-h-24 md:max-h-28 max-w-full w-auto object-contain opacity-100">
                                <span class="sponsor-fallback hidden text-sm md:text-base font-semibold text-gray-800 text-center">{{ $sponsor->nom }}</span>
                            @else
                                <span class="text-sm md:text-base font-semibold text-gray-800 text-center">{{ $sponsor->nom }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-white/60">No sponsors configured for this event yet.</div>
        @endif
    </div>
</section>




<script>
    function setCountdownValues(months, days, hours, minutes, seconds) {
        const elMonths = document.getElementById('months');
        const elDays = document.getElementById('days');
        const elHours = document.getElementById('hours');
        const elMinutes = document.getElementById('minutes');
        const elSeconds = document.getElementById('seconds');
        if (!elMonths || !elDays || !elHours || !elMinutes || !elSeconds) return;

        elMonths.textContent = String(months).padStart(2, '0');
        elDays.textContent = String(days).padStart(2, '0');
        elHours.textContent = String(hours).padStart(2, '0');
        elMinutes.textContent = String(minutes).padStart(2, '0');
        elSeconds.textContent = String(seconds).padStart(2, '0');
    }

    function updateCountdown() {
        const eventDate = new Date('{{ optional($evenement->date_heure_debut)->toIso8601String() }}');
        if (Number.isNaN(eventDate.getTime())) {
            setCountdownValues(0, 0, 0, 0, 0);
            return false;
        }

        const now = new Date().getTime();
        const distance = eventDate.getTime() - now;

        if (distance <= 0) {
            setCountdownValues(0, 0, 0, 0, 0);
            return false;
        }

        const months = Math.floor(distance / (1000 * 60 * 60 * 24 * 30));
        const days = Math.floor((distance % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        setCountdownValues(months, days, hours, minutes, seconds);
        return true;
    }

    let countdownInterval = null;
    function startCountdown() {
        if (!updateCountdown()) return;
        countdownInterval = setInterval(function () {
            if (!updateCountdown() && countdownInterval) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    }

    // Tab switching
    function switchTab(day) {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-white/20', 'text-white');
            btn.classList.add('text-white/70');
        });
        const activeTab = document.getElementById('tab-' + day);
        if (activeTab) {
            activeTab.classList.add('bg-white/20', 'text-white');
            activeTab.classList.remove('text-white/70');
        }

        let hasMatch = false;
        document.querySelectorAll('.schedule-item').forEach(item => {
            const isMatch = (item.dataset.day || '').trim() === day;
            item.style.display = isMatch ? '' : 'none';
            if (isMatch) hasMatch = true;
        });

        const emptyState = document.getElementById('schedule-empty');
        if (emptyState) {
            emptyState.classList.toggle('hidden', hasMatch);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        startCountdown();
        const firstTab = document.querySelector('.tab-btn');
        if (firstTab) {
            const firstDay = firstTab.id.replace('tab-', '');
            switchTab(firstDay);
        }
    });
</script>

@endsection
