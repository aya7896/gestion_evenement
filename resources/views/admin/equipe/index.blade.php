@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Mon équipe</h1>
        <p class="text-slate-600 dark:text-slate-400">Gérez les membres de votre équipe (admins et scanners).</p>
    </div>
    <div class="mb-6">
        <a href="{{ route('admin.collaborateurs.create') }}" class="btn-primary inline-flex items-center">
            <i class="fas fa-user-plus mr-2"></i> Ajouter un membre
        </a>
    </div>
    <div class="bg-white dark:bg-neutral-900 rounded-lg shadow p-6">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Prénom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Téléphone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Rôle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-neutral-900 divide-y divide-slate-200 dark:divide-slate-700">
                @foreach($collaborateurs as $collaborateur)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collaborateur->user->nom }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collaborateur->user->prenom }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collaborateur->user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $collaborateur->user->telephone ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($collaborateur->role) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($collaborateur->active)
                                <span class="badge badge-success">Actif</span>
                            @else
                                <span class="badge badge-danger">Inactif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.collaborateurs.edit', $collaborateur) }}" class="btn-secondary btn-sm mr-2">Modifier</a>
                            <a href="{{ route('admin.collaborateurs.show', $collaborateur) }}" class="btn-info btn-sm">Consulter</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
