@extends('layouts.app')

@section('content')
<div class="container-custom">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Sponsors</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">Liste des sponsors disponibles</p>
        </div>
        <a href="{{ route('admin.partenaires.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Ajouter un sponsor
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead class="bg-neutral-50 dark:bg-neutral-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Nom</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Site</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                        @forelse($partenaires as $partenaire)
                            <tr>
                                <td class="px-4 py-3">{{ $partenaire->nom }}</td>
                                <td class="px-4 py-3 uppercase">{{ $partenaire->type }}</td>
                                <td class="px-4 py-3">{{ $partenaire->email ?: '-' }}</td>
                                <td class="px-4 py-3">{{ $partenaire->site_web ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-neutral-500">Aucun sponsor pour le moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $partenaires->links() }}</div>
        </div>
    </div>
</div>
@endsection

