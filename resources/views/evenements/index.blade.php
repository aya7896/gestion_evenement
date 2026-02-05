@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-orange-600 dark:text-orange-400 tracking-tight mb-1">Mes Événements</h1>
                <p class="text-base text-slate-500 dark:text-slate-300">Gérez et organisez vos événements professionnels</p>
            </div>
            @if(auth()->user()->collaborateurs()->first() && auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
                <a href="{{ route('evenements.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-semibold shadow transition">
                    <i class="fas fa-plus"></i>
                    <span>Créer un Événement</span>
                </a>
            @endif
        </div>

        <!-- Affichage groupé par entreprise pour superadmin et admin_entreprise -->
        @if(auth()->user()->role === 'super_admin')
            @php
                $grouped = $evenements->groupBy('entreprise.id_entreprise');
            @endphp
            @foreach($grouped as $entrepriseId => $evts)
                @php $entreprise = $evts->first()->entreprise; @endphp
                <div id="company-events-{{ $entreprise->id_entreprise }}" class="mb-12">
                    <div class="bg-gradient-to-r from-orange-100 to-rose-100 dark:from-slate-800 dark:to-slate-800/70 border border-orange-200 dark:border-slate-700 rounded-xl p-5 mb-4 flex items-center gap-4 shadow-sm">
                        <div class="w-12 h-12 bg-orange-500 dark:bg-orange-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $entreprise->nom }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $entreprise->adresse }}</p>
                        </div>
                        <div class="ml-auto flex items-center gap-4">
                            @php
                                $total_evenements = isset($evts) ? $evts->count() : (isset($data) ? ($data['total_evenements'] ?? 0) : 0);
                                $total_ateliers = isset($evts) ? $evts->sum(fn($e) => $e->ateliers->count()) : (isset($data) ? ($data['total_ateliers'] ?? 0) : 0);
                            @endphp
                            <div class="text-sm text-center">
                                <div class="text-xs text-slate-500">Événements</div>
                                <div class="font-bold text-orange-600">{{ $total_evenements }}</div>
                            </div>
                            <div class="text-sm text-center">
                                <div class="text-xs text-slate-500">Ateliers</div>
                                <div class="font-bold text-blue-600">{{ $total_ateliers }}</div>
                            </div>
                            <a href="#company-events-{{ $entreprise->id_entreprise ?? 'me' }}" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm hover:bg-slate-200 transition">Voir les événements</a>
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="min-w-full divide-y divide-orange-200 dark:divide-slate-700 bg-white dark:bg-slate-900">
                            <thead class="bg-orange-50 dark:bg-slate-800">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Image</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Titre</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Mode</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Lieu</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Capacité</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Dates</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Statut</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-orange-100 dark:divide-slate-800">
                                @forelse($evts as $evenement)
                                    <tr class="hover:bg-orange-50 dark:hover:bg-slate-800/60 transition">
                                        <td class="px-4 py-3">
                                            @if($evenement->image)
                                                <img src="{{ asset('storage/' . $evenement->image) }}" alt="Image" class="h-12 w-12 rounded object-cover border border-orange-200 dark:border-slate-700 shadow-sm">
                                            @else
                                                <span class="inline-block w-12 h-12 bg-slate-200 dark:bg-slate-700 rounded"></span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-100">{{ $evenement->titre }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 text-xs font-semibold">
                                                <i class="fas fa-tag"></i> {{ ucfirst($evenement->type) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($evenement->mode === 'présentiel')
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                                                    <i class="fas fa-building"></i> Présentiel
                                                </span>
                                            @elseif($evenement->mode === 'en ligne')
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-semibold">
                                                    <i class="fas fa-wifi"></i> En ligne
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-violet-100 dark:bg-violet-900 text-violet-700 dark:text-violet-300 text-xs font-semibold">
                                                    <i class="fas fa-shuffle"></i> Hybride
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $evenement->lieu }}</td>
                                        <td class="px-4 py-3 text-center text-slate-700 dark:text-slate-200">{{ $evenement->capacite }}</td>
                                        <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300">
                                            <div><i class="fas fa-play text-emerald-500 mr-1"></i>{{ $evenement->date_heure_debut->format('d/m/Y H:i') }}</div>
                                            @if($evenement->date_heure_fin)
                                                <div><i class="fas fa-stop text-rose-500 mr-1"></i>{{ $evenement->date_heure_fin->format('d/m/Y H:i') }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($evenement->status === 'published')
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                                                    <i class="fas fa-circle-check"></i> Publié
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-rose-100 dark:bg-rose-900 text-rose-700 dark:text-rose-300 text-xs font-semibold">
                                                    <i class="fas fa-circle-xmark"></i> Inactif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('evenements.show', $evenement->id_event) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold shadow transition">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                            <div class="flex flex-col items-center gap-2">
                                                <i class="fas fa-calendar-xmark text-4xl text-orange-300 dark:text-orange-700"></i>
                                                <span class="font-semibold">Aucun événement pour cette entreprise</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @elseif(auth()->user()->collaborateurs()->first() && auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
            {{-- Vue pour admin_entreprise : affichage identique au super admin mais pour son entreprise --}}
            @php
                $collab = auth()->user()->collaborateurs()->first();
                $entreprise = $collab->entreprise;
                $evts = $evenements; // Déjà filtrés dans le contrôleur
            @endphp
            <div id="company-events-{{ $entreprise->id_entreprise ?? 'me' }}" class="mb-12">
                <div class="bg-gradient-to-r from-orange-100 to-rose-100 dark:from-slate-800 dark:to-slate-800/70 border border-orange-200 dark:border-slate-700 rounded-xl p-5 mb-4 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 bg-orange-500 dark:bg-orange-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $entreprise->nom }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $entreprise->adresse }}</p>
                    </div>
                    <div class="ml-auto flex items-center gap-4">
                        @php
                            $total_evenements = $evts->count();
                            $total_ateliers = $evts->sum(fn($e) => $e->ateliers->count());
                        @endphp
                        <div class="text-sm text-center">
                            <div class="text-xs text-slate-500">Événements</div>
                            <div class="font-bold text-orange-600">{{ $total_evenements }}</div>
                        </div>
                        <div class="text-sm text-center">
                            <div class="text-xs text-slate-500">Ateliers</div>
                            <div class="font-bold text-blue-600">{{ $total_ateliers }}</div>
                        </div>
                        <a href="#company-events-{{ $entreprise->id_entreprise ?? 'me' }}" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm hover:bg-slate-200 transition">Voir les événements</a>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full divide-y divide-orange-200 dark:divide-slate-700 bg-white dark:bg-slate-900">
                        <thead class="bg-orange-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Image</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Titre</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Mode</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Lieu</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Capacité</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Dates</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-orange-100 dark:divide-slate-800">
                            @forelse($evts as $evenement)
                                <tr class="hover:bg-orange-50 dark:hover:bg-slate-800/60 transition">
                                    <td class="px-4 py-3">
                                        @if($evenement->image)
                                            <img src="{{ asset('storage/' . $evenement->image) }}" alt="Image" class="h-12 w-12 rounded object-cover border border-orange-200 dark:border-slate-700 shadow-sm">
                                        @else
                                            <span class="inline-block w-12 h-12 bg-slate-200 dark:bg-slate-700 rounded"></span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-100">{{ $evenement->titre }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 text-xs font-semibold">
                                            <i class="fas fa-tag"></i> {{ ucfirst($evenement->type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($evenement->mode === 'présentiel')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                                                <i class="fas fa-building"></i> Présentiel
                                            </span>
                                        @elseif($evenement->mode === 'en ligne')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-semibold">
                                                <i class="fas fa-wifi"></i> En ligne
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-violet-100 dark:bg-violet-900 text-violet-700 dark:text-violet-300 text-xs font-semibold">
                                                <i class="fas fa-shuffle"></i> Hybride
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $evenement->lieu }}</td>
                                    <td class="px-4 py-3 text-center text-slate-700 dark:text-slate-200">{{ $evenement->capacite }}</td>
                                    <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300">
                                        <div><i class="fas fa-play text-emerald-500 mr-1"></i>{{ $evenement->date_heure_debut->format('d/m/Y H:i') }}</div>
                                        @if($evenement->date_heure_fin)
                                            <div><i class="fas fa-stop text-rose-500 mr-1"></i>{{ $evenement->date_heure_fin->format('d/m/Y H:i') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($evenement->status === 'published')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                                                <i class="fas fa-circle-check"></i> Publié
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-rose-100 dark:bg-rose-900 text-rose-700 dark:text-rose-300 text-xs font-semibold">
                                                <i class="fas fa-circle-xmark"></i> Inactif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('evenements.show', $evenement->id_event) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold shadow transition">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <i class="fas fa-calendar-xmark text-4xl text-orange-300 dark:text-orange-700"></i>
                                            <span class="font-semibold">Aucun événement pour cette entreprise</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
@endsection