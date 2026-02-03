@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Informations sur l'entreprise</h1>
        <p class="text-slate-600 dark:text-slate-400">Consultez les informations de votre entreprise.</p>
    </div>
    <div class="bg-white dark:bg-neutral-900 rounded-lg shadow p-6">
        @if(auth()->user()->collaborateurs()->first() && auth()->user()->collaborateurs()->first()->role === 'admin_entreprise')
        <div class="mb-4 text-right">
            <a href="{{ route('admin.entreprises.edit', $entreprise) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                <i class="fas fa-edit mr-2"></i> Modifier les informations
            </a>
        </div>
        @endif
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Nom</dt>
                <dd class="text-slate-900 dark:text-white">{{ $entreprise->nom }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Email</dt>
                <dd class="text-slate-900 dark:text-white">{{ $entreprise->email }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Téléphone</dt>
                <dd class="text-slate-900 dark:text-white">{{ $entreprise->tel ?? $entreprise->telephone }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Adresse</dt>
                <dd class="text-slate-900 dark:text-white">{{ $entreprise->adresse }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Ville</dt>
                <dd class="text-slate-900 dark:text-white">{{ $entreprise->ville }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Secteur d'activité</dt>
                <dd class="text-slate-900 dark:text-white">{{ $entreprise->secteur_activite }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Taille</dt>
                <dd class="text-slate-900 dark:text-white">{{ $entreprise->taille_entreprise }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Statut</dt>
                <dd class="text-slate-900 dark:text-white">{{ $entreprise->status }}</dd>
            </div>
            <div class="md:col-span-2">
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Description</dt>
                <dd class="text-slate-900 dark:text-white">{{ $entreprise->description }}</dd>
            </div>
            @if($entreprise->site_web)
            <div class="md:col-span-2">
                <dt class="font-semibold text-slate-700 dark:text-slate-200">Site web</dt>
                <dd class="text-slate-900 dark:text-white"><a href="{{ $entreprise->site_web }}" target="_blank" class="text-blue-600 underline">{{ $entreprise->site_web }}</a></dd>
            </div>
            @endif
        </dl>
    </div>
</div>
@endsection
