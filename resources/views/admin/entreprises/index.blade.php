@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-bold text-gradient-orange mb-2">Gestion des Entreprises</h1>
                <p class="text-slate-600 dark:text-slate-400 text-lg">Liste des entreprises enregistrées</p>
            </div>
            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('admin.entreprises.create') }}" class="btn-primary inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    <span>Ajouter une Entreprise</span>
                </a>
            @endif
        </div>

        <!-- Table des entreprises -->
        <div class="glass-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-gradient-to-r from-slate-50 to-orange-50/30 dark:from-slate-800 dark:to-slate-800/50">
                        <tr>
                            
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Logo</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Nom</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Secteur</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Taille</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Téléphone</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Ville</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">détails</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/50 dark:bg-slate-900/50 divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($entreprises as $entreprise)
                            <tr class="hover:bg-gradient-to-r hover:from-orange-50/50 hover:to-rose-50/50 dark:hover:from-slate-800/50 dark:hover:to-slate-800/30 transition-all duration-300 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $logoPath = $entreprise->logo;
                                        $logoExists = $logoPath ? \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath) : false;
                                    @endphp
                                    @if($logoExists)
                                        <div class="w-16 h-16 rounded-full overflow-hidden shadow-md bg-white/50">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($logoPath) }}" alt="{{ $entreprise->nom }}" class="w-full h-full object-cover object-center block" loading="lazy">
                                        </div>
                                    @else
                                        <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-rose-100 dark:from-orange-900/30 dark:to-rose-900/30 rounded-full flex items-center justify-center shadow-md">
                                            <i class="fas fa-building text-2xl text-orange-500 opacity-60"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $entreprise->nom }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600 dark:text-slate-400">{{ $entreprise->secteur_activite }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600 dark:text-slate-400">{{ $entreprise->taille_entreprise ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600 dark:text-slate-400">{{ $entreprise->tel ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600 dark:text-slate-400">{{ $entreprise->ville }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.entreprises.infos', ['entreprise' => $entreprise->id_entreprise ?? $entreprise->id ?? $entreprise]) }}" class="btn btn-info btn-sm bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 border-blue-200 dark:border-blue-800 hover:bg-blue-200 dark:hover:bg-blue-800">
                                            <i class="fas fa-eye mr-1.5"></i>Consulter
                                        </a>
                                        @if(auth()->user()->role !== 'super_admin')
                                        <a href="{{ route('admin.entreprises.edit', $entreprise) }}" class="btn-secondary btn-sm">
                                            <i class="fas fa-edit mr-1.5"></i>Modifier
                                        </a>
                                            <form action="{{ route('admin.entreprises.destroy', $entreprise) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger btn-sm">
                                                    <i class="fas fa-trash mr-1.5"></i>Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Aucune entreprise trouvée</h3>
                                        <p class="text-slate-600 dark:text-slate-400 mb-8">Créez une nouvelle entreprise pour commencer.</p>
                                        <a href="{{ route('admin.entreprises.create') }}" class="btn-primary">
                                            <i class="fas fa-plus mr-2"></i>
                                            <span>Ajouter une entreprise</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection