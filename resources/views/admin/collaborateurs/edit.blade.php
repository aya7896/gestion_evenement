@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto">
        <x-card>
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Modifier le Collaborateur</h1>

        <form action="{{ route('admin.collaborateurs.update', $collaborateur) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Utilisateur</label>
                    <div class="px-3 py-2 bg-gray-100 rounded-md">
                        {{ $collaborateur->user->nom }} {{ $collaborateur->user->prenom }} ({{ $collaborateur->user->email }})
                    </div>
                </div>

                @if(auth()->user()->role === 'super_admin')
                    <div>
                        <label for="id_entreprise" class="block text-sm font-medium text-gray-700 mb-2">Entreprise</label>
                        <select name="id_entreprise" id="id_entreprise" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Sélectionner une entreprise</option>
                            @foreach($entreprises as $entreprise)
                                <option value="{{ $entreprise->id_entreprise }}" {{ $entreprise->id_entreprise == $collaborateur->id_entreprise ? 'selected' : '' }}>
                                    {{ $entreprise->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_entreprise')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <input type="hidden" name="id_entreprise" value="{{ $collaborateur->id_entreprise }}">
                @endif

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                    <select name="role" id="role" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="collaborateur" {{ old('role', $collaborateur->role) == 'collaborateur' ? 'selected' : '' }}>Collaborateur</option>
                        <option value="admin_entreprise" {{ old('role', $collaborateur->role) == 'admin_entreprise' ? 'selected' : '' }}>Admin Entreprise</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="active" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="active" id="active" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="1" {{ old('active', $collaborateur->active) == 1 ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('active', $collaborateur->active) == 0 ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.collaborateurs.index') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-neutral-100 text-neutral-800 hover:bg-neutral-200">Annuler</a>
                <button type="submit" class="inline-flex items-center px-3 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700">Mettre à jour</button>
            </div>
        </form>
        </x-card>
    </div>
</div>
@endsection