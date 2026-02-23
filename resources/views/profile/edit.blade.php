@extends('layouts.app')

@section('title', 'Parametres')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white dark:bg-slate-800/90 rounded-2xl shadow border border-slate-200/50 dark:border-slate-700/50 p-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Parametres du compte</h1>
        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
            Modifiez vos informations personnelles et choisissez votre mode d affichage.
        </p>
    </div>

    <div class="bg-white dark:bg-slate-800/90 rounded-2xl shadow border border-slate-200/50 dark:border-slate-700/50 p-6">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Informations personnelles</h2>

        @if (session('status') === 'profile-updated')
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3">
                Vos informations ont ete mises a jour.
            </div>
        @endif

        <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="prenom" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Prenom</label>
                    <input id="prenom" name="prenom" type="text" value="{{ old('prenom', $user->prenom) }}"
                           class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 focus:border-orange-500 focus:ring-orange-500">
                    @error('prenom') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nom" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nom</label>
                    <input id="nom" name="nom" type="text" value="{{ old('nom', $user->nom) }}"
                           class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 focus:border-orange-500 focus:ring-orange-500">
                    @error('nom') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                           class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 focus:border-orange-500 focus:ring-orange-500">
                    @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Telephone</label>
                    <input id="telephone" name="telephone" type="text" value="{{ old('telephone', $user->telephone) }}"
                           class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 focus:border-orange-500 focus:ring-orange-500">
                    @error('telephone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-semibold transition">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800/90 rounded-2xl shadow border border-slate-200/50 dark:border-slate-700/50 p-6">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Apparence</h2>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Choisissez le mode d affichage de l interface.</p>

        <div class="flex flex-wrap gap-3">
            <button type="button"
                    @click="darkMode = false"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:border-orange-500 transition">
                <i class="fas fa-sun mr-2"></i> Mode clair
            </button>
            <button type="button"
                    @click="darkMode = true"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:border-orange-500 transition">
                <i class="fas fa-moon mr-2"></i> Mode sombre
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800/90 rounded-2xl shadow border border-red-200/60 dark:border-red-900/40 p-6">
        <h2 class="text-lg font-semibold text-red-700 dark:text-red-400 mb-3">Zone dangereuse</h2>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
            Supprimer votre compte est irreversible.
        </p>
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-3">
            @csrf
            @method('delete')
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mot de passe actuel</label>
                <input id="password" name="password" type="password"
                       class="w-full md:w-80 rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 focus:border-red-500 focus:ring-red-500">
                @error('password', 'userDeletion') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition">
                Supprimer le compte
            </button>
        </form>
    </div>
</div>
@endsection
