@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-bold text-gradient-orange mb-2">Ateliers</h1>
                <p class="text-slate-600 dark:text-slate-400 text-lg">Gérez les ateliers associés aux événements</p>
            </div>
            @if(auth()->user()->collaborateurs()->first() && auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
                <a href="{{ route('ateliers.create') }}" class="btn-primary inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    <span>Créer un atelier</span>
                </a>
            @endif
        </div>

        @if($ateliers->isEmpty())
            <div class="glass-card py-16 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-orange-100 to-rose-100 dark:from-orange-900/30 dark:to-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-6 shadow-md">
                    <i class="fas fa-chalkboard-teacher text-3xl text-orange-500 opacity-80"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Aucun atelier trouvé</h3>
                <p class="text-lg text-slate-600 dark:text-slate-400 mb-8 max-w-md mx-auto leading-relaxed">
                    @if(auth()->user()->role === 'super_admin' || auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
                        Créez votre premier atelier pour commencer à organiser des sessions interactives.
                    @else
                        Aucun atelier n'est actuellement disponible.
                    @endif
                </p>
                @if(auth()->user()->role === 'super_admin' || auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
                    <a href="{{ route('ateliers.create') }}" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        <span>Créer votre premier atelier</span>
                    </a>
                @endif
            </div>
        @else
            @if(auth()->user()->role === 'super_admin')
                @php
                    $grouped = $ateliers->groupBy('evenement.entreprise.id_entreprise');
                @endphp
                @foreach($grouped as $entrepriseId => $ateliersByEntreprise)
                    @php $entreprise = $ateliersByEntreprise->first()->evenement->entreprise; @endphp
                    <div class="mb-10">
                        <div class="bg-gradient-to-r from-orange-50 to-rose-50 dark:from-slate-800 dark:to-slate-800/70 border border-orange-200 dark:border-slate-700 rounded-lg p-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-orange-500 dark:bg-orange-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-building text-white text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $entreprise->nom }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $entreprise->adresse }}</p>
                                </div>
                            </div>
                        </div>
                        @php $groupedByEvent = $ateliersByEntreprise->groupBy('evenement.id_event'); @endphp
                        @foreach($groupedByEvent as $eventId => $ateliersByEvent)
                            @php $event = $ateliersByEvent->first()->evenement; @endphp
                            <div class="mb-6 ml-4">
                                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-slate-700 dark:to-slate-800 border border-blue-200 dark:border-slate-700 rounded-lg p-3 mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-blue-500 dark:bg-blue-600 rounded flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-white text-xs"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-base font-semibold text-blue-800 dark:text-blue-200 mb-0">{{ $event->titre }}</h4>
                                            <p class="text-xs text-blue-600 dark:text-blue-300 mb-0">{{ $event->date_heure_debut ? $event->date_heure_debut->format('d/m/Y') : '' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($ateliersByEvent as $atelier)
                                        <div class="glass-card group hover:translate-y-[-4px] transition-all duration-300">
                                            <div class="p-6">
                                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-orange-500 transition-colors">{{ $atelier->titre }}</h3>
                                                <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2">{{ $atelier->description }}</p>
                                                <div class="grid grid-cols-2 gap-3 mb-5 mt-3">
                                                    <div class="bg-white/40 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-center">
                                                        <div class="flex items-center justify-center text-xs text-slate-500 dark:text-slate-400 mb-1">
                                                            <i class="fas fa-calendar-day mr-1.5 text-orange-500/60"></i>
                                                            <span>Date</span>
                                                        </div>
                                                        <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                            {{ \Illuminate\Support\Carbon::parse($atelier->date)->format('d/m/Y') }}
                                                        </div>
                                                    </div>
                                                    <div class="bg-white/40 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-center">
                                                        <div class="flex items-center justify-center text-xs text-slate-500 dark:text-slate-400 mb-1">
                                                            <i class="fas fa-clock mr-1.5 text-orange-500/60"></i>
                                                            <span>Horaire</span>
                                                        </div>
                                                        <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                            {{ $atelier->heure_debut }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between mb-6">
                                                    <div class="flex items-center px-3 py-1.5 bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-900/30 rounded-full">
                                                        <i class="fas fa-users mr-2 text-green-600 text-xs"></i>
                                                        <span class="text-xs font-bold text-green-700 dark:text-green-400">
                                                            {{ $atelier->capacite }} places
                                                        </span>
                                                    </div>
                                                    <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-xs font-medium border border-slate-200 dark:border-slate-700">
                                                        {{ $atelier->type }}
                                                    </span>
                                                </div>
                                                <div class="flex gap-2">
                                                    <a href="{{ route('evenements.show', $atelier->evenement) }}" class="btn-secondary flex-1 inline-flex justify-center items-center py-2 text-sm">
                                                        <i class="fas fa-eye mr-6"></i>voir événement
                                                    </a>
                                                    @if(auth()->user()->role === 'super_admin' || auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
                                                        <a href="{{ route('evenements.ateliers.edit', [$atelier->evenement, $atelier]) }}" class="btn-secondary flex-1 inline-flex justify-center items-center py-2 text-sm">
                                                            <i class="fas fa-edit mr-2"></i>Modifier
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                @php $isAdminEntreprise = auth()->user()->collaborateurs()->first()?->role === 'admin_entreprise'; @endphp
                @if($isAdminEntreprise)
                    {{-- Grouper les ateliers par événement pour l'admin_entreprise --}}
                    @php $groupedByEvent = $ateliers->groupBy('evenement.id_event'); @endphp
                    @foreach($groupedByEvent as $eventId => $ateliersByEvent)
                        @php $event = $ateliersByEvent->first()->evenement; @endphp
                        <div class="mb-6">
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-slate-700 dark:to-slate-800 border border-blue-200 dark:border-slate-700 rounded-lg p-3 mb-2">
                                <div class="flex items-center gap-2 justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-blue-500 dark:bg-blue-600 rounded flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-white text-xs"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-base font-semibold text-blue-800 dark:text-blue-200 mb-0">{{ $event->titre }}</h4>
                                            <p class="text-xs text-blue-600 dark:text-blue-300 mb-0">{{ $event->date_heure_debut ? $event->date_heure_debut->format('d/m/Y') : '' }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('evenements.show', $event->id_event) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded bg-orange-500 text-white text-sm">Voir l'événement</a>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($ateliersByEvent as $atelier)
                                    <div class="glass-card group hover:translate-y-[-4px] transition-all duration-300">
                                        <div class="p-6">
                                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-orange-500 transition-colors">{{ $atelier->titre }}</h3>
                                            <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2">{{ $atelier->description }}</p>
                                            <div class="grid grid-cols-2 gap-3 mb-5 mt-3">
                                                <div class="bg-white/40 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-center">
                                                    <div class="flex items-center justify-center text-xs text-slate-500 dark:text-slate-400 mb-1">
                                                        <i class="fas fa-calendar-day mr-1.5 text-orange-500/60"></i>
                                                        <span>Date</span>
                                                    </div>
                                                    <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                        {{ \Illuminate\Support\Carbon::parse($atelier->date)->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                                <div class="bg-white/40 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-center">
                                                    <div class="flex items-center justify-center text-xs text-slate-500 dark:text-slate-400 mb-1">
                                                        <i class="fas fa-clock mr-1.5 text-orange-500/60"></i>
                                                        <span>Horaire</span>
                                                    </div>
                                                    <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                        {{ $atelier->heure_debut }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between mb-6">
                                                <div class="flex items-center px-3 py-1.5 bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-900/30 rounded-full">
                                                    <i class="fas fa-users mr-2 text-green-600 text-xs"></i>
                                                    <span class="text-xs font-bold text-green-700 dark:text-green-400">{{ $atelier->capacite }} places</span>
                                                </div>
                                                <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-xs font-medium border border-slate-200 dark:border-slate-700">{{ $atelier->type }}</span>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ route('evenements.show', $atelier->evenement) }}" class="btn-secondary flex-1 inline-flex justify-center items-center py-2 text-sm">
                                                    <i class="fas fa-eye mr-6"></i>voir événement
                                                </a>
                                                @if(auth()->user()->role === 'super_admin' || auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
                                                    <a href="{{ route('evenements.ateliers.edit', [$atelier->evenement, $atelier]) }}" class="btn-secondary flex-1 inline-flex justify-center items-center py-2 text-sm">
                                                        <i class="fas fa-edit mr-2"></i>Modifier
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($ateliers as $atelier)
                            <div class="glass-card group hover:translate-y-[-4px] transition-all duration-300">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-orange-500 transition-colors">{{ $atelier->titre }}</h3>
                                            <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2">{{ $atelier->description }}</p>
                                        </div>
                                        <div class="ml-4 bg-gradient-to-br from-orange-50 to-rose-50 dark:from-slate-800 dark:to-slate-700 p-3 rounded-xl shadow-sm border border-orange-100/50 dark:border-slate-600">
                                            <i class="fas fa-chalkboard-teacher text-orange-500 text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50/50 dark:bg-slate-800/50 rounded-xl p-3 mb-4 border border-slate-100 dark:border-slate-700">
                                        <div class="flex items-center text-sm">
                                            <i class="fas fa-calendar-alt mr-2 text-orange-500/70"></i>
                                            <span class="font-medium text-slate-700 dark:text-slate-300">Événement :</span>
                                            <span class="ml-2 text-slate-900 dark:text-white font-semibold">{{ $atelier->evenement->titre }}</span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 mb-5">
                                        <div class="bg-white/40 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-center">
                                            <div class="flex items-center justify-center text-xs text-slate-500 dark:text-slate-400 mb-1">
                                                <i class="fas fa-calendar-day mr-1.5 text-orange-500/60"></i>
                                                <span>Date</span>
                                            </div>
                                            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ \Illuminate\Support\Carbon::parse($atelier->date)->format('d/m/Y') }}</div>
                                        </div>
                                        <div class="bg-white/40 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-center">
                                            <div class="flex items-center justify-center text-xs text-slate-500 dark:text-slate-400 mb-1">
                                                <i class="fas fa-clock mr-1.5 text-orange-500/60"></i>
                                                <span>Horaire</span>
                                            </div>
                                            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $atelier->heure_debut }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="flex items-center px-3 py-1.5 bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-900/30 rounded-full">
                                            <i class="fas fa-users mr-2 text-green-600 text-xs"></i>
                                            <span class="text-xs font-bold text-green-700 dark:text-green-400">{{ $atelier->capacite }} places</span>
                                        </div>
                                        <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-xs font-medium border border-slate-200 dark:border-slate-700">{{ $atelier->type }}</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('evenements.show', $atelier->evenement) }}" class="btn-secondary flex-1 inline-flex justify-center items-center py-2 text-sm">
                                            <i class="fas fa-eye mr-6"></i>voir événement
                                        </a>
                                        @if(auth()->user()->role === 'super_admin' || auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
                                            <a href="{{ route('evenements.ateliers.edit', [$atelier->evenement, $atelier]) }}" class="btn-secondary flex-1 inline-flex justify-center items-center py-2 text-sm">
                                                <i class="fas fa-edit mr-2"></i>Modifier
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">{{ $ateliers->links() }}</div>
                @endif
            @endif
        @endif
    </div>
@endsection