@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Collaborateurs</h1>
                    <a href="{{ route('admin.collaborateurs.create') }}" 
                       class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i> Ajouter un collaborateur
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Grouped Collaborators by Enterprise -->
                @php
                    $grouped = $collaborateurs->groupBy(function($collab) {
                        return optional($collab->entreprise)->id_entreprise ?: 'sans_entreprise';
                    });
                @endphp
                @foreach($grouped as $entrepriseId => $entrepriseCollaborateurs)
                    @php
                        $entreprise = $entrepriseCollaborateurs->first()->entreprise ?? null;
                    @endphp
                    <div class="mb-8">
                        <div class="bg-gradient-to-r from-orange-50 to-rose-50 dark:from-slate-800 dark:to-slate-800/70 border border-orange-200 dark:border-slate-700 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-orange-500 dark:bg-orange-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-building text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                            {{ $entreprise ? $entreprise->nom : 'Sans entreprise liée' }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ $entreprise ? $entreprise->adresse : '' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Collaborateurs: {{ $entrepriseCollaborateurs->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rôle</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($entrepriseCollaborateurs as $collaborateur)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-rose-400 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                                                        {{ substr($collaborateur->user->nom, 0, 1) }}{{ substr($collaborateur->user->prenom, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $collaborateur->user->nom }} {{ $collaborateur->user->prenom }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $collaborateur->id_user }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500 dark:text-gray-300">{{ $collaborateur->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    <i class="fas fa-user-tag mr-2"></i>{{ ucfirst($collaborateur->role) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($collaborateur->active)
                                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        <i class="fas fa-check-circle mr-2"></i>Actif
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        <i class="fas fa-times-circle mr-2"></i>Inactif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.collaborateurs.show', $collaborateur) }}" 
                                                       class="btn btn-info text-xs bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 border-blue-200 dark:border-blue-800 hover:bg-blue-200 dark:hover:bg-blue-800">
                                                        <i class="fas fa-eye mr-1"></i> Consulter
                                                    </a>
                                                    <a href="{{ route('admin.collaborateurs.edit', $collaborateur) }}" 
                                                       class="btn btn-secondary text-xs">
                                                        <i class="fas fa-edit mr-1"></i> Modifier
                                                    </a>
                                                    <form action="{{ route('admin.collaborateurs.destroy', $collaborateur) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-danger text-xs"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce collaborateur ?')">
                                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

                @if($collaborateurs->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-gray-400 dark:text-gray-300 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun collaborateur</h3>
                        <p class="text-gray-500 dark:text-gray-400">Commencez par ajouter votre premier collaborateur.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn {
        @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md transition-all duration-200;
    }
    
    .btn-primary {
        @apply bg-gradient-to-r from-orange-500 to-rose-500 hover:from-orange-600 hover:to-rose-600 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-1;
    }
    
    .btn-secondary {
        @apply bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600;
    }
    
    .btn-danger {
        @apply bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 border-red-200 dark:border-red-800 hover:bg-red-200 dark:hover:bg-red-800;
    }
    
    .btn:hover {
        @apply shadow-md;
    }
</style>
@endpush