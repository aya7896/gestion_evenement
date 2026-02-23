{{-- resources/views/templates/luxury/confirmation.blade.php --}}
@extends('landing.Template 2.layouts.app')

@section('title', 'Confirmation')

@section('content')
<section class="min-h-screen pt-32 pb-20 bg-black">
    <div class="container mx-auto px-6 max-w-3xl">
        
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 border-2 border-yellow-500 rounded-full mb-6">
                <i class="fas fa-check text-yellow-500 text-4xl"></i>
            </div>
            <h1 class="text-5xl font-bold text-white font-serif mb-4">Confirmation</h1>
            <div class="w-24 h-[1px] bg-yellow-500 mx-auto mb-4"></div>
            <p class="text-gray-400 font-serif">Votre présence est enregistrée</p>
        </div>

        <div class="bg-neutral-950 border border-yellow-500/30 p-8 md:p-12 mb-8">
            <div class="text-center mb-8 pb-8 border-b border-yellow-500/20">
                <span class="text-yellow-500 uppercase tracking-[0.3em] text-xs font-serif block mb-2">Événement</span>
                <h2 class="text-3xl font-bold text-white font-serif">{{ $inscription->evenement->titre }}</h2>
                <p class="text-gray-400 mt-2 font-serif">
                    {{ $inscription->evenement->date_heure_debut->format('d F Y \à H:i') }} — {{ $inscription->evenement->lieu }}
                </p>
            </div>

            <div class="flex items-center justify-center mb-8">
                @if($inscription->photo)
                    <img src="{{ asset('storage/' . $inscription->photo) }}" 
                         alt="{{ $inscription->user->prenom }}"
                         class="w-32 h-32 rounded-full object-cover border-4 border-yellow-500/30">
                @else
                    <div class="w-32 h-32 rounded-full border-2 border-yellow-500 flex items-center justify-center">
                        <i class="fas fa-user text-yellow-500 text-4xl"></i>
                    </div>
                @endif
            </div>

            <div class="text-center space-y-2">
                <h3 class="text-2xl font-bold text-white font-serif">
                    {{ $inscription->user->prenom }} {{ $inscription->user->nom }}
                </h3>
                <p class="text-yellow-500/80 font-serif">{{ $inscription->user->email }}</p>
                @if($inscription->company)
                    <p class="text-gray-400 font-serif italic">{{ $inscription->poste }} — {{ $inscription->company }}</p>
                @endif
            </div>

            <div class="mt-8 pt-8 border-t border-yellow-500/20 text-center">
                <p class="text-gray-500 text-sm font-serif mb-2">Un email de confirmation a été envoyé à</p>
                <p class="text-white font-serif">{{ $inscription->user->email }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('public.evenement.landing', $inscription->evenement) }}"
               class="block bg-yellow-500 text-black text-center py-4 uppercase tracking-[0.2em] text-sm font-bold hover:bg-yellow-400 transition">
                Retour à l'événement
            </a>
            <button onclick="window.print()"
                    class="block border border-yellow-500 text-yellow-500 text-center py-4 uppercase tracking-[0.2em] text-sm font-bold hover:bg-yellow-500 hover:text-black transition">
                Imprimer l'invitation
            </button>
        </div>

        <div class="mt-8 text-center">
            <button onclick="if(confirm('Êtes-vous sûr de vouloir annuler votre inscription ?')) { document.getElementById('cancel-form').submit(); }" 
                    class="text-red-500/60 hover:text-red-500 transition text-sm font-serif uppercase tracking-widest">
                Annuler ma participation
            </button>
            <form id="cancel-form" action="{{ route('inscription.cancel', $inscription) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</section>
@endsection
