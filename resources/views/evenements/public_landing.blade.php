@extends('layouts.app')

@section('title', $evenement->titre)

@section('content')
<div class="max-w-2xl mx-auto py-10">
    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-emerald-100 text-emerald-800 border border-emerald-300">
            {{ session('success') }}
        </div>
    @endif
    <div class="bg-white dark:bg-slate-900 rounded-lg shadow p-6 space-y-6">
        <h1 class="text-3xl font-bold text-orange-600 mb-2">{{ $evenement->titre }}</h1>
        <div class="text-slate-600 dark:text-slate-300 mb-4">
            <strong>Type :</strong> {{ ucfirst($evenement->type) }}<br>
            <strong>Mode :</strong> {{ ucfirst($evenement->mode) }}<br>
            <strong>Lieu :</strong> {{ $evenement->lieu }}<br>
            <strong>Capacité :</strong> {{ $evenement->capacite }}<br>
            <strong>Début :</strong> {{ $evenement->date_heure_debut->format('d/m/Y H:i') }}<br>
            @if($evenement->date_heure_fin)
                <strong>Fin :</strong> {{ $evenement->date_heure_fin->format('d/m/Y H:i') }}<br>
            @endif
        </div>
        @if($evenement->image)
            <img src="{{ asset('storage/' . $evenement->image) }}" alt="Image événement" class="w-full rounded mb-4">
        @endif
        <h2 class="text-xl font-semibold text-orange-500 mb-2">Ateliers</h2>
        <ul class="list-disc pl-6 mb-4">
            @forelse($evenement->ateliers as $atelier)
                <li>
                    <strong>{{ $atelier->titre }}</strong> - {{ $atelier->description }}<br>
                    <span class="text-xs text-slate-500">{{ $atelier->date_heure_debut->format('d/m/Y H:i') }} @if($atelier->date_heure_fin) - {{ $atelier->date_heure_fin->format('d/m/Y H:i') }} @endif</span>
                </li>
            @empty
                <li>Aucun atelier pour cet événement.</li>
            @endforelse
        </ul>
        <hr>
        <h2 class="text-xl font-semibold text-orange-500 mb-2">Inscription à l'événement</h2>
        <form method="POST" action="{{ route('public.evenement.inscription', $evenement->id_event) }}" class="space-y-4">
            @csrf
            <div>
                <label for="nom" class="block text-sm font-medium">Nom</label>
                <input type="text" name="nom" id="nom" class="mt-1 block w-full rounded border-slate-300" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full rounded border-slate-300" required>
            </div>
            <button type="submit" class="w-full py-2 px-4 rounded bg-orange-500 hover:bg-orange-600 text-white font-semibold">S'inscrire</button>
        </form>
    </div>
</div>
@endsection