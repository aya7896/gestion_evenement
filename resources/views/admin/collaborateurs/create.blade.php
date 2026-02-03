@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto">
        <x-card>
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Créer un Collaborateur</h1>

        <form action="{{ route('admin.collaborateurs.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations Utilisateur -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Utilisateur</h3>
                </div>
                
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                    <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('prenom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                    <input type="password" name="password" id="password"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Informations Collaborateur -->
                <div class="md:col-span-2 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Collaborateur</h3>
                </div>

                <div>
                    <label for="id_entreprise" class="block text-sm font-medium text-gray-700 mb-2">Entreprise</label>
                    @php
                        $user = auth()->user();
                        $userCollab = $user->collaborateurs()->first();
                        $disableEntreprise = !$user->isSuperAdmin() && $userCollab; // disable only if admin_entreprise has a collaborator profile
                        $selectedEntreprise = old('id_entreprise', $userCollab->id_entreprise ?? '');
                    @endphp

                    <select name="id_entreprise" id="id_entreprise" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            {{ $disableEntreprise ? 'disabled' : '' }}>
                        <option value="">Sélectionner une entreprise</option>
                        @foreach($entreprises as $entreprise)
                            <option value="{{ $entreprise->id_entreprise }}" 
                                    {{ $selectedEntreprise == $entreprise->id_entreprise ? 'selected' : '' }}>
                                {{ $entreprise->nom }}
                            </option>
                        @endforeach
                    </select>

                    @if($disableEntreprise)
                        {{-- Hidden input to ensure disabled select value is submitted for validation --}}
                        <input type="hidden" name="id_entreprise" value="{{ $selectedEntreprise }}">
                    @endif

                    @error('id_entreprise')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                    <select name="role" id="role" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @if(auth()->user()->role === 'super_admin')
                            <option value="admin_entreprise" {{ old('role') == 'admin_entreprise' ? 'selected' : '' }}>Admin Entreprise</option>
                        @endif
                        <option value="scanner" {{ old('role') == 'scanner' ? 'selected' : '' }}>Scanner</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="active" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="active" id="active" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="1" {{ old('active', 1) == 1 ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('active') == 0 ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.collaborateurs.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Créer le collaborateur</button>
            </div>
        </form>
        </x-card>
    </div>
</div>
@endsection
