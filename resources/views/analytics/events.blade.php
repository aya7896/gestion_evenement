@extends('layouts.app')

@section('title', 'Analytics - Vos événements')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">← Retour au dashboard</a>
        </div>
        <div>
            <a href="{{ route('analytics.export') }}" class="inline-flex items-center px-3 py-2 bg-emerald-600 text-white rounded shadow text-sm hover:bg-emerald-700">
                Exporter CSV
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800/90 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Analytics - Vos événements</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-500">
                    <tr>
                        <th class="px-4 py-2">Événement</th>
                        <th class="px-4 py-2">Début</th>
                        <th class="px-4 py-2">Ateliers</th>
                        <th class="px-4 py-2">Inscrits</th>
                        <th class="px-4 py-2">Validés</th>
                        <th class="px-4 py-2">Taux validés</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($eventsDetails as $ev)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $ev['titre'] }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($ev['date_debut'])->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ number_format($ev['ateliers_count'], 0, ',', ' ') }}</td>
                        <td class="px-4 py-3">{{ number_format($ev['registered'], 0, ',', ' ') }}</td>
                        <td class="px-4 py-3">{{ number_format($ev['validated'], 0, ',', ' ') }}</td>
                        <td class="px-4 py-3">{{ $ev['attendance_rate'] }}%</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('evenements.show', $ev['id']) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded text-sm">Détails</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">Aucun événement trouvé pour votre entreprise.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
