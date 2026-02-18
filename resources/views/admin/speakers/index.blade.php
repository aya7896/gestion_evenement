@extends('layouts.app')

@section('content')
<div class="container-custom">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Speakers</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">Liste des speakers disponibles</p>
        </div>
        <a href="{{ route('admin.speakers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Ajouter un speaker
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead class="bg-neutral-50 dark:bg-neutral-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Nom</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Poste</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Entreprise</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                        @forelse($speakers as $speaker)
                            <tr>
                                <td class="px-4 py-3">{{ $speaker->prenom }} {{ $speaker->nom }}</td>
                                <td class="px-4 py-3">{{ $speaker->email ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $speaker->poste ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $speaker->company ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-neutral-500">Aucun speaker pour le moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $speakers->links() }}</div>
        </div>
    </div>
</div>
@endsection

