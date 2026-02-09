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
                    <p class="text-3xl font-bold text-slate-900 dark:text-white mt-1">{{ $groupedData->sum('total_ateliers') }}</p>
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
                            @if($data['entreprise']->logo)
                                <img src="{{ asset('storage/' . $data['entreprise']->logo) }}" alt="{{ $data['entreprise']->nom }}" class="w-16 h-16 rounded-xl object-cover border-2 border-slate-200 dark:border-slate-700">
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
                        <div class="grid gap-4">
                            @foreach($data['evenements'] as $evenement)
                                <div class="border border-slate-200/50 dark:border-slate-700/50 rounded-xl p-4 hover:shadow-md transition-all duration-200">
                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $evenement->titre }}</h3>
                                            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $evenement->description }}</p>
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                <span class="px-2 py-1 bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-400 text-xs rounded-lg border border-orange-200 dark:border-orange-800/30">
                                                    {{ $evenement->type }}
                                                </span>
                                                <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs rounded-lg border border-slate-200 dark:border-slate-600">
                                                    {{ $evenement->mode }}
                                                </span>
                                                <span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 text-xs rounded-lg border border-emerald-200 dark:border-emerald-800/30">
                                                    {{ $evenement->capacite }} places
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-2 text-right">
                                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                                <svg class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($evenement->date_heure_debut)->format('d M Y H:i') }}
                                            </div>
                                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                                <svg class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $evenement->lieu }}
                                            </div>
                                            <div class="flex gap-2 justify-end">
                                                <a href="{{ route('evenements.show', $evenement->id_event) }}" class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                                                    Voir l'événement
                                                </a>
                                                <a href="https://wa.me/?text={{ urlencode(route('public.evenement.landing', $evenement->id_event)) }}" target="_blank" title="Partager sur WhatsApp" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 text-black font-semibold shadow-md transition-all duration-200 ml-2">
                                                    Partager
                                                </a>
                                                @if($evenement->ateliers->count() > 0)
                                                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 rounded-lg text-sm border border-blue-200 dark:border-blue-800/30">
                                                        {{ $evenement->ateliers->count() }} atelier(s)
                                                    </span>
                                                @endif
                                            </div>
                                        @push('scripts')
                                        <script>
                                        function copyShareLink(link) {
                                            navigator.clipboard.writeText(link).then(function() {
                                                alert('Lien copié dans le presse-papier !');
                                            }, function() {
                                                alert('Impossible de copier le lien.');
                                            });
                                        }
                                        </script>
                                        @endpush
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