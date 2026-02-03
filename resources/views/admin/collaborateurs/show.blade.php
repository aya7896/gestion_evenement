@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-8">
        <h1 class="text-2xl font-bold mb-2">Fiche Collaborateur</h1>
        <p class="text-slate-600 dark:text-slate-400">Détails du collaborateur</p>
    </div>
    <div class="bg-white dark:bg-neutral-900 rounded-lg shadow p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Nom</dt>
                <dd class="text-slate-900 dark:text-white">{{ $collaborateur->user->nom }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Prénom</dt>
                <dd class="text-slate-900 dark:text-white">{{ $collaborateur->user->prenom }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Email</dt>
                <dd class="text-slate-900 dark:text-white">{{ $collaborateur->user->email }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Téléphone</dt>
                <dd class="text-slate-900 dark:text-white">{{ $collaborateur->user->telephone ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Rôle</dt>
                <dd class="text-slate-900 dark:text-white">{{ ucfirst($collaborateur->role) }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Statut</dt>
                <dd class="text-slate-900 dark:text-white">{{ $collaborateur->active ? 'Actif' : 'Inactif' }}</dd>
            </div>
            <div class="md:col-span-2">
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Entreprise</dt>
                <dd class="text-slate-900 dark:text-white">{{ $collaborateur->entreprise->nom ?? '-' }}</dd>
            </div>
        </dl>
        <div class="mt-8 text-right">
            <a href="{{ route('admin.equipe.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">Retour à la liste</a>
        </div>
    </div>
</div>
@endsection
