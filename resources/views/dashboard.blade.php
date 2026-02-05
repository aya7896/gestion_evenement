@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="max-w-7xl mx-auto space-y-8">

    <!-- En-tête avec animation -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 animate-fade-in">
        <div>
            <h1 class="text-4xl lg:text-5xl font-bold text-slate-900 dark:text-white tracking-tight">
                Bienvenue{{ auth()->user()->prenom ? ', ' . auth()->user()->prenom : '' }} 👋
            </h1>
            <p class="mt-2 text-slate-600 dark:text-slate-400">
                Voici un aperçu de vos événements et statistiques
            </p>
        </div>

        @unless(auth()->user()->role === 'super_admin' || auth()->user()->collaborateurs()->first()?->role === 'admin_entreprise')
        <div class="flex flex-col items-end gap-2">
            <div class="text-right">
                <p class="text-sm text-slate-500 dark:text-slate-400">Aujourd'hui</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">
                    {{ now()->format('d M Y') }}
                </p>
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    {{ now()->format('l') }}
                </p>
            </div>
        </div>
        @endunless
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        @if (isset($stats['evenements']))
        <!-- Événements -->
        <div class="group relative bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-rose-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-orange-500/10 to-rose-500/10 rounded-xl">
                        <svg class="w-8 h-8 text-orange-600 dark:text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @if (isset($stats['events_growth']))
                    <span class="flex items-center text-sm font-medium {{ $stats['events_growth'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="{{ $stats['events_growth'] >= 0 ? 'M12 7a1 1 0 011-1h5a1 1 0 011 1v5a1 1 0 11-2 0V9.414l-6.293 6.293a1 1 0 01-1.414 0L6 12.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L10 13.586 15.586 8H13a1 1 0 01-1-1z' : 'M8 13a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5a1 1 0 112 0v3.586l6.293-6.293a1 1 0 011.414 0L14 13.586l4.293-4.293a1 1 0 011.414 1.414l-5 5a1 1 0 01-1.414 0L10 12.414 4.414 18H7a1 1 0 011-1z' }}" clip-rule="evenodd" />
                        </svg>
                        {{ abs($stats['events_growth']) }}%
                    </span>
                    @endif
                </div>
                <h3 class="text-sm font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-1">
                    Événements
                </h3>
                <p class="text-4xl font-extrabold bg-gradient-to-r from-orange-600 to-rose-600 bg-clip-text text-transparent">
                    {{ number_format($stats['evenements'], 0, ',', ' ') }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                    Total des événements
                </p>
            </div>
        </div>
        @endif

        @if (isset($stats['ateliers']))
        <!-- Ateliers -->
        <div class="group relative bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-1">
                    Ateliers
                </h3>
                <p class="text-4xl font-extrabold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">
                    {{ number_format($stats['ateliers'], 0, ',', ' ') }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                    Sessions disponibles
                </p>
            </div>
        </div>
        @endif

        @if (isset($stats['total_participants']))
        <!-- Participants -->
        <div class="group relative bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-xl">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    @if (isset($stats['participants_growth']))
                    <span class="flex items-center text-sm font-medium {{ $stats['participants_growth'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="{{ $stats['participants_growth'] >= 0 ? 'M12 7a1 1 0 011-1h5a1 1 0 011 1v5a1 1 0 11-2 0V9.414l-6.293 6.293a1 1 0 01-1.414 0L6 12.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L10 13.586 15.586 8H13a1 1 0 01-1-1z' : 'M8 13a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5a1 1 0 112 0v3.586l6.293-6.293a1 1 0 011.414 0L14 13.586l4.293-4.293a1 1 0 011.414 1.414l-5 5a1 1 0 01-1.414 0L10 12.414 4.414 18H7a1 1 0 011-1z' }}" clip-rule="evenodd" />
                        </svg>
                        {{ abs($stats['participants_growth']) }}%
                    </span>
                    @endif
                </div>
                <h3 class="text-sm font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-1">
                    Participants
                </h3>
                <p class="text-4xl font-extrabold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                    {{ number_format($stats['total_participants'], 0, ',', ' ') }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                    Inscriptions totales
                </p>
            </div>
        </div>
        @endif

        @if (auth()->user()->isSuperAdmin() && isset($stats['entreprises']))
        <!-- Entreprises -->
        <div class="group relative bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-violet-500/10 to-purple-500/10 rounded-xl">
                        <svg class="w-8 h-8 text-violet-600 dark:text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-1">
                    Entreprises
                </h3>
                <p class="text-4xl font-extrabold bg-gradient-to-r from-violet-600 to-purple-600 bg-clip-text text-transparent">
                    {{ number_format($stats['entreprises'], 0, ',', ' ') }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                    Partenaires actifs
                </p>
            </div>
        </div>
        @endif

    </div>

    <!-- Grille 2 colonnes : Cartes de navigation -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Carte Voir les événements -->
        <a href="{{ route('evenements.index') }}" class="group relative bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 flex flex-col h-full">
                <div class="flex-shrink-0 flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-teal-500/10 to-cyan-500/10 rounded-xl">
                        <svg class="w-8 h-8 text-teal-600 dark:text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="flex-grow">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Voir les événements</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Consultez la liste de tous les événements à venir et passés.</p>
                </div>
                <div class="mt-4 flex-shrink-0">
                    <span class="font-medium text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300">
                        Accéder à la liste →
                    </span>
                </div>
            </div>
        </a>

        <!-- Carte Voir les ateliers -->
        <a href="{{ route('ateliers.index') }}" class="group relative bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 flex flex-col h-full">
                <div class="flex-shrink-0 flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
                <div class="flex-grow">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Voir les ateliers</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Explorez toutes les sessions et ateliers disponibles.</p>
                </div>
                <div class="mt-4 flex-shrink-0">
                    <span class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                        Accéder à la liste →
                    </span>
                </div>
            </div>
        </a>
    </div>

    <!-- Événements à venir -->
    <div class="bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Événements à venir</h3>
        <div class="space-y-4">
            @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
                @foreach($upcomingEvents as $event)
                <div class="flex gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 hover:border-orange-300 dark:hover:border-orange-700 transition-all duration-200">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-orange-500 to-rose-600 flex flex-col items-center justify-center text-white shadow-md">
                            <span class="text-xs font-semibold uppercase">{{ \Carbon\Carbon::parse($event->date_heure_debut)->format('M') }}</span>
                            <span class="text-xl font-bold">{{ \Carbon\Carbon::parse($event->date_heure_debut)->format('d') }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-slate-900 dark:text-white truncate">{{ $event->nom }}</h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                            <svg class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($event->date_heure_debut)->format('H:i') }}
                        </p>
                        @if($event->lieu)
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            <svg class="inline w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $event->lieu }}
                        </p>
                        @endif
                    </div>
                    <div class="flex items-center">
                        <span class="px-3 py-1 rounded-lg text-xs font-medium bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-400 border border-orange-200 dark:border-orange-800/30">
                            {{ $event->capacite }} places
                        </span>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-slate-600 dark:text-slate-400">Aucun événement à venir</p>
                </div>
            @endif
        </div>
    </div>


        <!-- Actions rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('evenements.create') }}" class="group relative bg-gradient-to-br from-orange-500 to-rose-600 rounded-2xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
            <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 text-white">
                <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Nouvel événement</h3>
                <p class="text-sm text-white/80">Créer un nouvel événement</p>
            </div>
        </a>

        <a href="{{ route('ateliers.create') }}" class="group relative bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Nouvel atelier</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400">Planifier une nouvelle session</p>
            </div>
        </a>

        @if (auth()->user()->role === 'super_admin')
        <a href="{{ route('admin.evenements.by-company') }}" class="group relative bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-gradient-to-br from-violet-500/10 to-purple-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Organisation</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400">Voir événements par entreprise</p>
            </div>
        </a>
        @else
        <a href="#" class="group relative bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-teal-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Voir les rapports</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400">Analyser les statistiques</p>
            </div>
        </a>
        @endif
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logique pour les statistiques et autres éléments interactifs si nécessaire
});
</script>
@endpush

@endsection