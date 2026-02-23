@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-6xl mx-auto space-y-6">
        
        <!-- En-tête avec image et titre -->
        <x-card>
            <div class="relative">
                @php
                    $imagePublic = $evenement->image && file_exists(public_path('storage/' . $evenement->image));
                    $imageAbsolute = $evenement->image && file_exists($evenement->image);
                @endphp

                @if($imagePublic)
                    <div class="w-full max-h-32 overflow-hidden rounded-xl bg-gray-200 dark:bg-gray-800">
    <img src="{{ asset('storage/' . $evenement->image) }}" alt="{{ $evenement->titre }}" class="w-full h-auto object-cover">
</div>

                @elseif($imageAbsolute)
                    <div class="w-full h-40 overflow-hidden rounded-xl bg-gray-200 dark:bg-gray-800">
                        <img src="{{ $evenement->image }}" alt="{{ $evenement->titre }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-full h-40 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center rounded-xl">
                        <svg class="w-20 h-20 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                
                <!-- Badges d'état et Menu -->
                <div class="absolute top-4 right-4 flex items-center space-x-2">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $evenement->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $evenement->status === 'active' ? 'Actif' : 'Inactif' }}
                    </span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $evenement->validation_superAdmin ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $evenement->validation_superAdmin ? 'Validé' : 'En attente' }}
                    </span>
                    
                    @if(auth()->user()->collaborateurs()->first() && auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
                    <!-- Menu trois points -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 rounded-full bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-700 transition-colors shadow-md">
                            <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="5" r="2"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                                <circle cx="12" cy="19" r="2"></circle>
                            </svg>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                            <!-- Modifier -->
                            <a href="{{ route('evenements.edit', $evenement) }}" class="w-full flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier
                            </a>
                            
                            <!-- Supprimer -->
                            <form action="{{ route('evenements.destroy', $evenement) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $evenement->titre }}</h1>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        {{ $evenement->entreprise->nom }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                        {{ ucfirst($evenement->type) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($evenement->mode) }}
                    </span>
                </div>
            </div>
        </x-card>


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

        <!-- Carte Informations compacte (horizontale) -->
        <x-card>
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Informations</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center">
                <!-- icône -->
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Capacité</p>
                <p class="text-md font-semibold text-gray-900 dark:text-white">{{ $evenement->capacite }} places</p>
            </div>
        </div>

        <div class="flex items-center gap-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-xl">
            <div class="w-10 h-10 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                <!-- icône -->
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Inscriptions</p>
                <p class="text-md font-semibold text-gray-900 dark:text-white">{{ $evenement->inscriptions->count() }} / {{ $evenement->capacite }} inscrits</p>
            </div>
        </div>

        <div class="flex items-center gap-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-800 rounded-full flex items-center justify-center">
                <!-- icône -->
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Durée</p>
                <p class="text-md font-semibold text-gray-900 dark:text-white">{{ abs(\Illuminate\Support\Carbon::parse($evenement->date_heure_debut)->diffInDays(\Illuminate\Support\Carbon::parse($evenement->date_heure_fin))) }} jours</p>
            </div>
        </div>

        <div class="flex items-center gap-3 p-3 bg-orange-50 dark:bg-orange-900/20 rounded-xl">
            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-800 rounded-full flex items-center justify-center">
                <!-- icône -->
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Dates</p>
                <p class="text-md font-semibold text-gray-900 dark:text-white">{{ \Illuminate\Support\Carbon::parse($evenement->date_heure_debut)->format('d/m/Y H:i') }}</p>
                <p class="text-sm text-gray-500">au {{ \Illuminate\Support\Carbon::parse($evenement->date_heure_fin)->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
            <div class="w-10 h-10 bg-red-100 dark:bg-red-800 rounded-full flex items-center justify-center">
                <!-- icône -->
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Localisation</p>
                <p class="text-md font-semibold text-gray-900 dark:text-white">{{ $evenement->lieu }}</p>
                <p class="text-sm text-gray-500">{{ $evenement->localisation }}</p>
            </div>
        </div>
    </div>
</x-card>


        <!-- Description -->
        <x-card>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Description</h2>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $evenement->description }}</p>
        </x-card>

       <!-- Sponsors - Cartes rondes horizontales -->
<x-card>
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Sponsors</h2>
    <div class="flex flex-wrap gap-4">
        @foreach($evenement->partenaires as $partenaire)
            <div class="flex flex-col items-center group">
                <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 overflow-hidden flex items-center justify-center hover:border-blue-400 transition-colors">
                    @if($partenaire->logo_url)
                        <img src="{{ $partenaire->logo_url }}" alt="{{ $partenaire->nom }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl font-bold text-gray-400">{{ substr($partenaire->nom, 0, 2) }}</span>
                    @endif
                </div>
                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-2 text-center max-w-[80px] truncate">{{ $partenaire->nom }}</p>
                @if(auth()->user()->collaborateurs()->first() && auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
                    <form method="POST" action="{{ route('evenements.partenaires.detach', [$evenement, $partenaire]) }}" class="mt-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition-opacity">Retirer</button>
                    </form>
                @endif
            </div>
        @endforeach

        @if(auth()->user()->collaborateurs()->first() && auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
            <!-- Bouton Ajouter Sponsor -->
            <div class="flex flex-col items-center">
                <a href="{{ route('admin.partenaires.create') }}" class="w-20 h-20 rounded-full border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </a>
                <p class="text-xs text-gray-500 mt-2">Ajouter</p>
            </div>
        @endif
    </div>
</x-card>


        <!-- Modal Sponsor -->
        <div id="sponsorModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ajouter un sponsor</h3>
                    <button onclick="document.getElementById('sponsorModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Tabs -->
                <div class="flex border-b border-gray-200 dark:border-gray-600 mb-4">
                    <button id="tabExisting" onclick="switchSponsorTab('existing')" class="px-4 py-2 text-sm font-medium text-blue-600 border-b-2 border-blue-600">
                        Sponsor existant
                    </button>
                    <button id="tabNew" onclick="switchSponsorTab('new')" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400">
                        Nouveau sponsor
                    </button>
                </div>

                <!-- Tab 1: Existing Sponsor -->
                <div id="formExisting">
                    <form method="POST" action="{{ route('evenements.partenaires.attach', $evenement) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Partenaire</label>
                                <select name="id_partenaire" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                                    <option value="">Sélectionner un sponsor</option>
                                    @foreach(($availablePartenaires ?? collect()) as $partenaire)
                                        <option value="{{ $partenaire->id_partenaire }}">{{ $partenaire->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Montant (optionnel)</label>
                                <input type="number" step="0.01" name="montant" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Ex: 5000">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contribution (optionnel)</label>
                                <input type="text" name="contribution" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Description">
                            </div>
                            <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Ajouter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tab 2: New Sponsor -->
                <div id="formNew" class="hidden">
                    <form method="POST" action="{{ route('evenements.partenaires.createAndAttach', $evenement) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom du sponsor *</label>
                                <input type="text" name="nom" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" required placeholder="Nom du sponsor">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type *</label>
                                <select name="type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="gold">Gold</option>
                                    <option value="silver">Silver</option>
                                    <option value="bronze">Bronze</option>
                                    <option value="media">Media</option>
                                    <option value="institutionnel">Institutionnel</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo</label>
                                <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                    <input type="email" name="email" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="email@exemple.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
                                    <input type="text" name="telephone" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="+212...">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site web</label>
                                <input type="url" name="site_web" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="https://...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Description du sponsor"></textarea>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-600">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Montant</label>
                                    <input type="number" step="0.01" name="montant" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Ex: 5000">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contribution</label>
                                    <input type="text" name="contribution" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Description">
                                </div>
                            </div>
                            <button type="submit" class="w-full py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                Créer et ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function switchSponsorTab(tab) {
                const tabExisting = document.getElementById('tabExisting');
                const tabNew = document.getElementById('tabNew');
                const formExisting = document.getElementById('formExisting');
                const formNew = document.getElementById('formNew');
                
                if (tab === 'existing') {
                    tabExisting.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                    tabExisting.classList.remove('text-gray-500');
                    tabNew.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                    tabNew.classList.add('text-gray-500');
                    formExisting.classList.remove('hidden');
                    formNew.classList.add('hidden');
                } else {
                    tabNew.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                    tabNew.classList.remove('text-gray-500');
                    tabExisting.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                    tabExisting.classList.add('text-gray-500');
                    formNew.classList.remove('hidden');
                    formExisting.classList.add('hidden');
                }
            }
        </script>

        <!-- Ateliers - Cartes carrées horizontales -->
       <x-card>
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Ateliers</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($evenement->ateliers as $atelier)
            <a href="{{ route('evenements.ateliers.show', [$evenement, $atelier]) }}" class="block group">
                <div class="w-full rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 p-4 flex flex-col justify-between text-white shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                    <div>
                        <p class="text-xs font-semibold opacity-80">{{ $atelier->date->format('d/m') }}</p>
                        <p class="text-sm font-bold mt-1 line-clamp-2">{{ $atelier->titre }}</p>
                    </div>
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-xs opacity-80">{{ $atelier->heure_debut }}</span>
                        <span class="text-xs bg-white/25 px-2 py-1 rounded-full font-medium text-white/90">{{ optional($atelier->inscriptions)->count() ?? 0 }}/{{ $atelier->capacite }}</span>
                    </div>
                </div>
            </a>
        @endforeach

        @if(auth()->user()->collaborateurs()->first() && auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
            <!-- Bouton Ajouter Atelier -->
            <a href="{{ route('evenements.ateliers.create', $evenement) }}" class="block">
                <div class="w-full h-32 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex flex-col items-center justify-center hover:border-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <p class="text-xs text-gray-500 mt-2">Ajouter</p>
                </div>
            </a>
        @endif
    </div>
</x-card>


        <!-- Speakers - Cartes rondes horizontales -->
        
<x-card>
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Speakers</h2>
    @php
        // Récupérer tous les speakers de tous les ateliers de cet événement
        $speakers = collect();
        foreach($evenement->ateliers as $atelier) {
            $speakers = $speakers->merge($atelier->speakers);
        }
        $speakers = $speakers->unique('id_speaker');
    @endphp
    <div class="flex flex-wrap gap-4">
        @forelse($speakers as $speaker)
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 overflow-hidden flex items-center justify-center">
                    @if($speaker->photo)
                        <img src="{{ asset('storage/' . $speaker->photo) }}" alt="{{ $speaker->full_name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl font-bold text-gray-400">{{ substr($speaker->prenom, 0, 1) }}{{ substr($speaker->nom, 0, 1) }}</span>
                    @endif
                </div>
                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mt-2 text-center max-w-[80px] truncate">{{ $speaker->prenom }}</p>
                <p class="text-xs text-gray-500 truncate max-w-[80px]">{{ $speaker->poste }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-sm">Aucun speaker pour le moment</p>
        @endforelse

        @if(auth()->user()->collaborateurs()->first() && auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
            <!-- Bouton Ajouter Speaker -->
            <div class="flex flex-col items-center">
                <a href="{{ route('admin.speakers.create') }}" class="w-20 h-20 rounded-full border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all cursor-pointer">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </a>
                <p class="text-xs text-gray-500 mt-2">Ajouter</p>
            </div>
        @endif
    </div>
</x-card>


        <!-- Modal Speaker -->
        <div id="speakerModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ajouter un speaker</h3>
                    <button onclick="document.getElementById('speakerModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Les speakers sont associés aux ateliers. Vous pouvez :</p>
                    <a href="{{ route('admin.speakers.create') }}" class="block w-full py-3 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 transition-colors font-medium">
                        Créer un nouveau speaker
                    </a>
                    <a href="{{ route('admin.speakers.index') }}" class="block w-full py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                        Gérer les speakers existants
                    </a>
                </div>
            </div>
        </div>

        <!-- Inscriptions -->
        <x-card>
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Inscriptions ({{ $evenement->inscriptions->count() }})</h2>
                @if(!$evenement->inscriptions->isEmpty())
                    <a href="{{ route('inscriptions.export-csv', $evenement->id_event) }}" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white font-medium rounded-lg hover:bg-emerald-600 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Exporter CSV
                    </a>
                @endif
            </div>

            @if($evenement->inscriptions->isEmpty())
                <p class="text-center text-gray-500 py-8">Aucune inscription pour le moment</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Nom</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($evenement->inscriptions as $inscription)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $inscription->user->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $inscription->user->email ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $inscription->date_ins ? \Carbon\Carbon::parse($inscription->date_ins)->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($inscription->verified_at || $inscription->statut === 'validée')
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Vérifiée</span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En attente</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>

        <!-- Documents -->
        @if($evenement->plaquette_pdf)
        <x-card>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Documents</h2>
            <a href="{{ route('evenements.plaquette.download', $evenement) }}" class="inline-flex items-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Télécharger la plaquette PDF
            </a>
        </x-card>
        @endif

        <!-- Actions -->
        <div class="flex flex-wrap justify-end gap-3">
            <a href="{{ route('evenements.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Retour
            </a>
        </div>
    </div>
</div>
@endsection