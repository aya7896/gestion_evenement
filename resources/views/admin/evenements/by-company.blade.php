@extends('layouts.app')

@section('title', 'Événements par Entreprise')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- En-tête -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white">Événements par Entreprise</h1>
            <p class="mt-2 text-slate-600 dark:text-slate-400">Organisation des événements par entreprise pour une vue d'ensemble</p>
        </div>
        @if(Auth::user()->collaborateurs->where('role', 'admin_entreprise')->isNotEmpty())
            <a href="{{ route('admin.evenements.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                Ajouter un événement
            </a>
        @endif
    </div>

    <!-- Statistiques globales -->
    @if(Auth::user()->isSuperAdmin())
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">Total Entreprises</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $groupedData->count() }}</p>
                </div>
                <div class="p-3 bg-gradient-to-br from-violet-500/10 to-purple-500/10 rounded-xl">
                    <svg class="w-8 h-8 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">Total Événements</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $groupedData->sum('total_evenements') }}</p>
                </div>
                <div class="p-3 bg-gradient-to-br from-orange-500/10 to-rose-500/10 rounded-xl">
                    <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">Total Ateliers</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-red mt-1">{{ $groupedData->sum('total_ateliers') }}</p>
                </div>
                <div class="p-3 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide">Total Participants</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ number_format($groupedData->sum('total_participants'), 0, ',', ' ') }}</p>
                </div>
                <div class="p-3 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-xl">
                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Liste des entreprises -->
    <div class="space-y-6">
        @forelse($groupedData as $data)
            <div id="company-{{ $data['entreprise']->id_entreprise }}" class="bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
                <!-- En-tête de l'entreprise -->
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-6 border-b border-slate-200/50 dark:border-slate-700/50">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            @php
                                $logo = $data['entreprise']->logo ?? null;
                                $logoNorm = $logo ? str_replace('\\', '/', $logo) : null;
                                $logoNorm = $logoNorm ? preg_replace('#^(/)?(storage/|public/|storage/app/public/)#', '', $logoNorm) : null;

                                if ($logoNorm && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoNorm)) {
                                    $logoUrl = \Illuminate\Support\Facades\Storage::url($logoNorm);
                                } elseif ($logo && filter_var($logo, FILTER_VALIDATE_URL)) {
                                    $logoUrl = $logo;
                                } elseif ($logo && file_exists($logo)) {
                                    $logoUrl = asset($logo);
                                } else {
                                    $logoUrl = null;
                                }
                            @endphp
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $data['entreprise']->nom }}" class="w-16 h-16 rounded-xl object-cover border-2 border-slate-200 dark:border-slate-700">
                            @else
                                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-rose-600 rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                                    {{ substr($data['entreprise']->nom, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $data['entreprise']->nom }}</h2>
                                <p class="text-slate-600 dark:text-slate-400">{{ $data['entreprise']->secteur_activite }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $data['entreprise']->ville }}</p>
                            </div>
                        </div>
                        <div class="flex gap-4 text-right items-center">
                            <div class="text-sm">
                                <span class="text-slate-500 dark:text-slate-400">Événements:</span>
                                <span class="ml-2 font-bold text-orange-600 dark:text-orange-400">{{ $data['total_evenements'] }}</span>
                            </div>
                            <div class="text-sm">
                                <span class="text-slate-500 dark:text-slate-400">Ateliers:</span>
                                <span class="ml-2 font-bold text-blue-600 dark:text-blue-400">{{ $data['total_ateliers'] }}</span>
                            </div>
                            <div>
                                <a href="#company-{{ $data['entreprise']->id_entreprise }}" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm hover:bg-slate-200 transition">Voir les événements</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des événements -->
                <div class="p-6">
                    @if($data['evenements']->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-slate-600 dark:text-slate-400">Aucun événement pour cette entreprise</p>
                        </div>
                    @else
                      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    @foreach($data['evenements'] as $evenement)
        @php
            $eventImg = $evenement->image ?? null;
            $eventImgNorm = $eventImg ? str_replace('\\', '/', $eventImg) : null;
            $eventImgNorm = $eventImgNorm ? preg_replace('#^(/)?(storage/|public/|storage/app/public/)#', '', $eventImgNorm) : null;

            if ($eventImgNorm && \Illuminate\Support\Facades\Storage::disk('public')->exists($eventImgNorm)) {
                $eventImgUrl = \Illuminate\Support\Facades\Storage::url($eventImgNorm);
            } elseif ($eventImg && filter_var($eventImg, FILTER_VALIDATE_URL)) {
                $eventImgUrl = $eventImg;
            } elseif ($eventImg && file_exists($eventImg)) {
                $eventImgUrl = asset($eventImg);
            } else {
                $eventImgUrl = null;
            }
        @endphp

        <div class="group relative bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 
                    rounded-[2.5rem] shadow-xl shadow-slate-200/60 dark:shadow-slate-900/60
                    border-2 border-slate-200/80 dark:border-slate-700/60 
                    overflow-hidden flex flex-col
                    hover:shadow-2xl hover:shadow-blue-500/25 dark:hover:shadow-blue-500/15
                    hover:-translate-y-2 hover:border-blue-300 dark:hover:border-blue-600
                    transition-all duration-300 ease-out min-h-[620px]">

            {{-- Badge Type Event --}}
            <div class="absolute top-4 left-4 z-10">
                <span class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider 
                             bg-gradient-to-r from-blue-600 to-indigo-600 text-white 
                             rounded-full shadow-lg shadow-blue-500/30">
                    {{ $evenement->type ?? 'Événement' }}
                </span>
            </div>

            {{-- Image Section --}}
            <div class="relative h-56 w-full bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 overflow-hidden rounded-t-[2.5rem]">
                @if($eventImgUrl)
                    <img src="{{ $eventImgUrl }}"
                         alt="{{ $evenement->titre }}"
                         loading="lazy"
                         onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&q=80';"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out rounded-t-[2.5rem]">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-t-[2.5rem]">
                        <div class="p-6 rounded-full bg-white/20 dark:bg-slate-700/50 backdrop-blur-sm">
                            <svg class="w-14 h-14 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Content Section --}}
            <div class="p-6 flex flex-col justify-between flex-1 space-y-5 rounded-b-[2.5rem]">

                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                        {{ $evenement->titre }}
                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed min-h-[66px]">
                        {{ \Illuminate\Support\Str::limit($evenement->description, 145) }}
                    </p>
                </div>

                {{-- Event Info Pills --}}
                <div class="flex flex-wrap gap-3">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium 
                                 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-xl">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ \Carbon\Carbon::parse($evenement->date_heure_debut)->format('d M Y') }}
                    </span>
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium 
                                 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-xl">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ \Carbon\Carbon::parse($evenement->date_heure_debut)->format('H:i') }}
                    </span>
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium 
                                 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-xl">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ Str::limit($evenement->lieu, 25) }}
                    </span>
                </div>

                {{-- Capacity & Ateliers Badges --}}
                @php
                    $ateliersCount = isset($evenement->ateliers_count)
                        ? (int) $evenement->ateliers_count
                        : ($evenement->relationLoaded('ateliers') ? $evenement->ateliers->count() : 0);
                @endphp
                <div class="flex flex-wrap items-center gap-4">
                    <span class="inline-flex items-center gap-2 px-4 py-2 min-w-[160px] text-sm font-semibold 
                                 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-full shadow-md shadow-orange-500/30">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        {{ (int) ($evenement->capacite ?? 0) }} places
                    </span>
                    <span class="inline-flex items-center gap-2 px-4 py-2 min-w-[160px] text-sm font-semibold 
                                 bg-gradient-to-r from-violet-500 to-purple-500 text-black rounded-full shadow-md shadow-violet-500/30">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        {{ $ateliersCount }} ateliers
                    </span>
                </div>

                {{-- Progress Bar Section --}}
@php
    $valides = $evenement->inscriptions_valides ?? 0;
    $total = $evenement->inscriptions_total ?? 0;
    $nonValides = $total - $valides;
    $capacite = $evenement->capacite ?: 1;
    $restantes = max(0, $capacite - $total);

    // Pourcentages relatifs à la capacité totale
    $pctValides = ($valides / $capacite) * 100;
    $pctNonValides = ($nonValides / $capacite) * 100;
    $pctRestantes = ($restantes / $capacite) * 100;
@endphp

<div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-4">
    {{-- Titre avec total --}}
    <div class="flex justify-between items-center mb-3">
        <span class="text-sm font-bold text-slate-700 dark:text-slate-200">
            📊 {{ $total }} / {{ $capacite }} inscrits
        </span>
        <span class="text-xs px-3 py-1 bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-full font-semibold">
            {{ $restantes }} disponibles
        </span>
    </div>

    {{-- Barre empilée --}}
    <div class="w-full h-4 rounded-full overflow-hidden flex bg-slate-200 dark:bg-slate-600 shadow-inner">
        @if($pctValides > 0)
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-400 h-full transition-all duration-500"
             style="width: {{ $pctValides }}%">
        </div>
        @endif
        @if($pctNonValides > 0)
        <div class="bg-gradient-to-r from-amber-400 to-yellow-400 h-full transition-all duration-500"
             style="width: {{ $pctNonValides }}%">
        </div>
        @endif
    </div>

    {{-- Légende --}}
    <div class="flex justify-between mt-3 text-xs text-slate-500 dark:text-slate-400 font-medium">
        <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
            {{ $valides }} vérifiées
        </span>
        <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
            {{ $nonValides }} en attente
        </span>
    </div>
</div>



                {{-- Buttons Section --}}
                <div class="flex gap-3 pt-3">
                    <a href="{{ route('evenements.show', $evenement->id_event) }}"
                       class="flex-1 text-center px-5 py-3 
                              bg-slate-100 dark:bg-slate-700 
                              text-slate-700 dark:text-slate-200
                              text-base font-semibold rounded-xl 
                              hover:bg-slate-200 dark:hover:bg-slate-600 
                              hover:shadow-lg
                              transition-all duration-200">
                        👁️ Voir détails
                    </a>

                    <a href="#" onclick="navigator.clipboard.writeText('{{ route('public.evenement.landing', $evenement) }}'); this.innerHTML='✅ Copié!'; setTimeout(() => this.innerHTML='🔗 Partager', 2000); return false;"
                       class="flex-1 text-center px-5 py-3 
                              bg-gradient-to-r from-emerald-500 to-teal-500 
                              hover:from-emerald-600 hover:to-teal-600
                              text-white text-base font-semibold rounded-xl 
                              shadow-lg shadow-emerald-500/25
                              hover:shadow-xl hover:shadow-emerald-500/30
                              transition-all duration-200">
                        🔗 Partager
                    </a>
                </div>

            </div>
        </div>

    @endforeach
</div>

                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <p class="text-slate-600 dark:text-slate-400 text-lg">Aucune entreprise trouvée</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
