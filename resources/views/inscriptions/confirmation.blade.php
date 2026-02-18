@extends('landing.layouts.app')

@section('title', 'Confirmation - ' . ($inscription->evenements->first()->titre ?? 'Inscription'))

@section('content')
@php
    $statut = strtolower((string) ($inscription->statut ?? ''));
    $isValidated = str_starts_with($statut, 'valid');
    $isVerified = !empty($inscription->verified_at) || $isValidated;
    $event = $inscription->evenements->first();
@endphp

<meta name="inscription-id" content="{{ $inscription->id_inscription }}">
<meta name="inscription-email" content="{{ $inscription->user->email ?? session('inscription_email') }}">

<section class="relative py-24 gradient-bg-dark min-h-screen overflow-hidden">
    <div class="absolute inset-0 hero-gradient opacity-70"></div>
    <div class="relative container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="scroll-reveal rounded-3xl p-6 md:p-10 bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl">
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/15 border border-white/25 mb-4">
                        <i class="fas {{ $isVerified ? 'fa-check-circle text-green-300' : 'fa-shield-alt text-conf-light' }} text-4xl"></i>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-bold text-white text-glow mb-2">
                        {{ $isVerified ? 'Inscription verifiee' : 'Verification de votre inscription' }}
                    </h1>
                    <p class="text-white/80 text-lg">{{ $event->titre ?? 'Evenement' }}</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-green-500/20 border border-green-400/40 text-green-100">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-400/40 text-red-100">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-400/40 text-red-100">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                        <p class="text-white/60 text-xs uppercase tracking-wide mb-1">Email</p>
                        <p class="text-white font-semibold break-all">{{ $inscription->user->email ?? session('inscription_email') ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                        <p class="text-white/60 text-xs uppercase tracking-wide mb-1">Numero inscription</p>
                        <p class="text-white font-semibold">#{{ $inscription->id_inscription }}</p>
                    </div>
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-4">
                        <p class="text-white/60 text-xs uppercase tracking-wide mb-1">Statut</p>
                        <p class="font-semibold {{ $isVerified ? 'text-green-300' : 'text-yellow-300' }}">
                            {{ $isVerified ? 'Verifiee' : 'En attente de verification' }}
                        </p>
                    </div>
                </div>

                @if(!$isVerified)
                    <div class="rounded-2xl p-6 bg-white/10 border border-white/20 mb-8">
                        <h2 class="text-white text-xl font-bold mb-2">Entrez votre code de confirmation</h2>
                        <p class="text-white/70 mb-4">Saisissez le code a 6 chiffres recu par email.</p>

                        <form method="POST" action="{{ route('inscription.verify.submit', $inscription) }}" class="space-y-4">
                            @csrf
                            <input
                                type="text"
                                name="code"
                                maxlength="6"
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                required
                                placeholder="123456"
                                class="w-full px-4 py-4 rounded-xl bg-white/15 border border-white/30 text-white text-center tracking-[0.35em] text-2xl placeholder-white/50 focus:outline-none focus:border-conf-light"
                            >
                            <button type="submit" class="w-full btn-gradient text-white py-4 rounded-full font-semibold">
                                Verifier mon inscription
                            </button>
                        </form>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                            <form method="POST" action="{{ route('inscription.verify.resend', $inscription) }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 rounded-xl border border-white/30 text-white hover:bg-white/10 transition">
                                    Renvoyer le code
                                </button>
                            </form>
                            <form method="POST" action="{{ route('inscription.verify.sms', $inscription) }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 rounded-xl border border-white/30 text-white hover:bg-white/10 transition">
                                    Essayer avec SMS
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ $evenement ? route('public.evenement.landing', $evenement) : url('/') }}"
                       class="inline-flex items-center justify-center px-6 py-3 rounded-full border border-white/30 text-white hover:bg-white hover:text-conf-dark transition">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>

                    @if($isVerified)
                        <a href="{{ route('inscription.badge.download', $inscription->id_inscription) }}"
                           class="inline-flex items-center justify-center px-6 py-3 rounded-full btn-gradient text-white font-semibold">
                            <i class="fas fa-download mr-2"></i> Badge
                        </a>
                    @else
                        <button type="button" disabled class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-white/10 text-white/60 border border-white/20 cursor-not-allowed">
                            <i class="fas fa-lock mr-2"></i> Badge bloque
                        </button>
                    @endif
                </div>

                <p class="text-center text-white/60 text-sm mt-8">
                    Un email de confirmation a ete envoye a
                    <strong class="text-white">{{ $inscription->user->email ?? session('inscription_email') ?? 'votre adresse email' }}</strong>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
