@extends('layouts.app')

@section('title', 'Gestion des Entreprises')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800">
        Gestion des Entreprises
    </h2>
    @if(auth()->user()->role === 'super_admin')
    <a href="{{ route('admin.entreprises.create') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700">
        Ajouter une Entreprise
    </a>
    @endif
</div>

@if($entreprises->isEmpty())
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune entreprise trouvée</h3>
        <p class="text-gray-500 mb-4">Commencez par ajouter votre première entreprise</p>
        @if(auth()->user()->role === 'super_admin')
        <a href="{{ route('admin.entreprises.create') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700">
            <i class="fas fa-plus mr-2"></i>
            Ajouter une Entreprise
        </a>
        @endif
    </div>
@else
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Entreprise
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Contact
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Localisation
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Secteur
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($entreprises as $entreprise)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($entreprise->logo)
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $entreprise->logo) }}" alt="{{ $entreprise->nom }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $entreprise->nom }}</div>
                                @if($entreprise->site_web)
                                    <div class="text-sm text-gray-500">
                                        <a href="{{ $entreprise->site_web }}" target="_blank" class="hover:text-indigo-600">
                                            <i class="fas fa-globe mr-1"></i>{{ $entreprise->site_web }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $entreprise->email }}</div>
                        <div class="text-sm text-gray-500">{{ $entreprise->tel }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $entreprise->ville }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($entreprise->adresse, 30) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $entreprise->secteur_activite }}
                        </span>
                        <div class="text-xs text-gray-500 mt-1">{{ $entreprise->taille_entreprise }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($entreprise->status === 'active')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.entreprises.edit', $entreprise->id_entreprise) }}" 
                               class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition-colors"
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(auth()->user()->role === 'super_admin')
                            <form action="{{ route('admin.entreprises.destroy', $entreprise->id_entreprise) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md transition-colors"
                                        title="Supprimer">
                                    Supprimer
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
