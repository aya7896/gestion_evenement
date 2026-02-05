@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Informations Entreprise</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Détails et statistiques de votre entreprise</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.entreprises.edit', $entreprise) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.entreprises.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Entreprise Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6">
            <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-building text-white text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $entreprise->nom }}</h2>
                        <p class="text-blue-100 mt-1">{{ $entreprise->secteur_activite }}</p>
                    </div>
                </div>
                <div class="text-right text-white">
                    <div class="text-sm opacity-80">Créée le</div>
                    <div class="text-lg font-semibold">{{ $entreprise->created_at->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Informations de base -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-2"></i>
                        Informations de base
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">Nom</label>
                            <p class="text-slate-900 dark:text-white font-medium">{{ $entreprise->nom }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">Secteur d'activité</label>
                            <p class="text-slate-900 dark:text-white font-medium">{{ $entreprise->secteur_activite }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">Taille</label>
                            <p class="text-slate-900 dark:text-white font-medium">{{ ucfirst($entreprise->taille_entreprise) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">Email</label>
                            <p class="text-slate-900 dark:text-white font-medium">{{ $entreprise->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">Téléphone</label>
                            <p class="text-slate-900 dark:text-white font-medium">{{ $entreprise->tel ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">Site web</label>
                            <p class="text-slate-900 dark:text-white font-medium">{{ $entreprise->site_web ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Adresse -->
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-blue-600 dark:text-blue-400 mr-2"></i>
                        Adresse
                    </h3>
                        <div class="space-y-2">
                        <p class="text-slate-900 dark:text-white">{{ $entreprise->adresse ?? '' }}</p>
                        <p class="text-slate-900 dark:text-white">{{ $entreprise->ville ?? '' }}</p>
                        <p class="text-slate-900 dark:text-white">{{ $entreprise->pays ?? '' }}</p>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="space-y-6">
                <div class="bg-gradient-to-br from-orange-50 to-rose-50 dark:from-slate-700 dark:to-slate-800 rounded-xl p-6 border border-orange-200 dark:border-slate-600">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-chart-bar text-orange-600 dark:text-orange-400 mr-2"></i>
                        Statistiques
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-white/50 dark:bg-white/10 rounded-lg">
                            <span class="text-slate-700 dark:text-slate-300">Collaborateurs</span>
                            <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $entreprise->collaborateurs->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-white/50 dark:bg-white/10 rounded-lg">
                            <span class="text-slate-700 dark:text-slate-300">Événements</span>
                            <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $entreprise->evenements->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-white/50 dark:bg-white/10 rounded-lg">
                            <span class="text-slate-700 dark:text-slate-300">Ateliers</span>
                            <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $entreprise->ateliers->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-bolt text-blue-600 dark:text-blue-400 mr-2"></i>
                        Actions rapides
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.collaborateurs.index') }}" 
                           class="flex items-center p-3 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors group">
                            <i class="fas fa-users text-blue-600 dark:text-blue-400 mr-3 group-hover:scale-110 transition-transform"></i>
                            <span class="text-slate-900 dark:text-white font-medium">Gérer les collaborateurs</span>
                        </a>
                        <a href="{{ route('evenements.index') }}" 
                           class="flex items-center p-3 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors group">
                            <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 mr-3 group-hover:scale-110 transition-transform"></i>
                            <span class="text-slate-900 dark:text-white font-medium">Voir les événements</span>
                        </a>
                        <a href="{{ route('ateliers.index') }}" 
                           class="flex items-center p-3 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors group">
                            <i class="fas fa-chalkboard-teacher text-blue-600 dark:text-blue-400 mr-3 group-hover:scale-110 transition-transform"></i>
                            <span class="text-slate-900 dark:text-white font-medium">Voir les ateliers</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Collaborateurs récents -->
    @if($entreprise->collaborateurs->isNotEmpty())
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden mb-8">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center">
                <i class="fas fa-users text-blue-600 dark:text-blue-400 mr-2"></i>
                Collaborateurs de l'entreprise
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($entreprise->collaborateurs as $collaborateur)
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-slate-900 dark:text-white">{{ $collaborateur->nom_collaborateur }} {{ $collaborateur->prenom_collaborateur }}</h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400">{{ ucfirst($collaborateur->role) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Événements récents -->
    @if($entreprise->evenements->isNotEmpty())
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 mr-2"></i>
                Derniers événements
            </h3>
            <div class="space-y-3">
                @foreach($entreprise->evenements->take(5) as $evenement)
                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-rose-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-slate-900 dark:text-white">{{ $evenement->nom_event }}</h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400">{{ $evenement->date_debut->format('d/m/Y') }} - {{ $evenement->date_fin->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            {{ $evenement->etat_event }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection