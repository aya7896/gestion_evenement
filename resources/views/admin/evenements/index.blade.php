@extends('layouts.app')

@section('title', 'Mes Événements')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-orange-600 dark:text-orange-400 tracking-tight mb-1">Mes Événements</h1>
            <p class="text-base text-slate-500 dark:text-slate-300">Liste des événements de votre entreprise</p>
        </div>
        <a href="{{ route('evenements.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-semibold shadow transition">
            <i class="fas fa-plus"></i>
            <span>Créer un Événement</span>
        </a>
    </div>

    {{-- Debug temporaire : affichage des titres des événements récupérés --}}
    <div class="mb-4 p-4 bg-yellow-100 text-yellow-900 rounded">
        <strong>Debug événements récupérés :</strong>
        <ul>
            @forelse($evenements as $evt)
                <li>{{ $evt->titre }}</li>
            @empty
                <li>Aucun événement transmis à la vue.</li>
            @endforelse
        </ul>
    </div>
    <!-- Filtre de recherche -->
    <form method="GET" action="" class="mb-6 flex flex-wrap gap-4 items-end bg-orange-50 dark:bg-slate-800 p-4 rounded-lg shadow">
        <div>
            <label for="search" class="block text-xs font-semibold text-orange-700 dark:text-orange-300 mb-1">Titre</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" class="input input-bordered w-48" placeholder="Rechercher un titre...">
        </div>
        <div>
            <label for="type" class="block text-xs font-semibold text-orange-700 dark:text-orange-300 mb-1">Type</label>
            <select name="type" id="type" class="input input-bordered w-40">
                <option value="">Tous</option>
                <option value="formation" {{ request('type') == 'formation' ? 'selected' : '' }}>Formation</option>
                <option value="atelier" {{ request('type') == 'atelier' ? 'selected' : '' }}>Atelier</option>
                <option value="conférence" {{ request('type') == 'conférence' ? 'selected' : '' }}>Conférence</option>
            </select>
        </div>
        <div>
            <label for="status" class="block text-xs font-semibold text-orange-700 dark:text-orange-300 mb-1">Statut</label>
            <select name="status" id="status" class="input input-bordered w-40">
                <option value="">Tous</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publié</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
            </select>
        </div>
        <div>
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-semibold shadow transition">
                <i class="fas fa-filter"></i>
                <span>Filtrer</span>
            </button>
            <a href="{{ route('evenements.index') }}" class="ml-2 text-orange-600 hover:underline text-sm">Réinitialiser</a>
        </div>
    </form>
    <div class="overflow-x-auto rounded-lg shadow">
        <table class="min-w-full divide-y divide-orange-200 dark:divide-slate-700 bg-white dark:bg-slate-900">
            <thead class="bg-orange-50 dark:bg-slate-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Titre</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Mode</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Lieu</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Capacité</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Dates</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-orange-100 dark:divide-slate-800">
                @forelse($evenements as $evenement)
                    <tr class="hover:bg-orange-50 dark:hover:bg-slate-800/60 transition">
                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-100">{{ $evenement->titre }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 text-xs font-semibold">
                                <i class="fas fa-tag"></i> {{ ucfirst($evenement->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($evenement->mode === 'présentiel')
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                                    <i class="fas fa-building"></i> Présentiel
                                </span>
                            @elseif($evenement->mode === 'en ligne')
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-semibold">
                                    <i class="fas fa-wifi"></i> En ligne
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-violet-100 dark:bg-violet-900 text-violet-700 dark:text-violet-300 text-xs font-semibold">
                                    <i class="fas fa-shuffle"></i> Hybride
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $evenement->lieu }}</td>
                        <td class="px-4 py-3 text-center text-slate-700 dark:text-slate-200">{{ $evenement->capacite }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300">
                            <div><i class="fas fa-play text-emerald-500 mr-1"></i>{{ $evenement->date_heure_debut->format('d/m/Y H:i') }}</div>
                            @if($evenement->date_heure_fin)
                                <div><i class="fas fa-stop text-rose-500 mr-1"></i>{{ $evenement->date_heure_fin->format('d/m/Y H:i') }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($evenement->status === 'published')
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                                    <i class="fas fa-circle-check"></i> Publié
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-rose-100 dark:bg-rose-900 text-rose-700 dark:text-rose-300 text-xs font-semibold">
                                    <i class="fas fa-circle-xmark"></i> Inactif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('evenements.show', $evenement->id_event) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold shadow transition">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                            <!-- Bouton de partage de la landing page -->
                            <button onclick="copyShareLink('{{ route('public.evenement.landing', $evenement->id_event) }}')" title="Partager l'événement" class="inline-flex items-center gap-1 px-3 py-1.5 rounded bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold shadow transition ml-2">
                                <i class="fas fa-share-nodes"></i> Partager
                            </button>
                            <a href="https://wa.me/?text={{ urlencode(route('public.evenement.landing', $evenement->id_event)) }}" target="_blank" title="Partager sur WhatsApp" class="inline-flex items-center gap-1 px-3 py-1.5 rounded bg-green-500 hover:bg-green-600 text-white text-xs font-semibold shadow transition ml-2">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <!-- Bouton de partage de la landing page -->
                            <button onclick="copyShareLink('{{ route('public.evenement.landing', $evenement->id_event) }}')" title="Partager l'événement" class="inline-flex items-center gap-1 px-3 py-1.5 rounded bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold shadow transition ml-2">
                                <i class="fas fa-share-nodes"></i> Partager
                            </button>
                            <a href="https://wa.me/?text={{ urlencode(route('public.evenement.landing', $evenement->id_event)) }}" target="_blank" title="Partager sur WhatsApp" class="inline-flex items-center gap-1 px-3 py-1.5 rounded bg-green-500 hover:bg-green-600 text-white text-xs font-semibold shadow transition ml-2">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fas fa-calendar-xmark text-4xl text-orange-300 dark:text-orange-700"></i>
                                <span class="font-semibold">Aucun événement trouvé</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyShareLink(link) {
    navigator.clipboard.writeText(link).then(function() {
        alert('Lien copié dans le presse-papier !');
    }, function() {
        alert('Impossible de copier le lien.');
    });
}
</script>
@endpush
