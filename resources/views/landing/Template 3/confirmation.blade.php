{{-- resources/views/templates/tech/confirmation.blade.php --}}
@extends('landing.Template 3.layouts.app')

@section('title', 'Registration_Complete')

@section('content')
<section class="min-h-screen pt-32 pb-20 bg-black flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-2xl">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 border-2 border-green-400 rounded-full mb-6 shadow-[0_0_30px_rgba(74,222,128,0.3)]">
                <i class="fas fa-check text-green-400 text-4xl"></i>
            </div>
            <div class="text-green-400 font-mono text-sm mb-2">[SUCCESS] Registration complete</div>
            <h1 class="text-4xl font-bold text-white font-mono">
                WELCOME_TO_THE<span class="text-cyan-400">_MATRIX</span>
            </h1>
        </div>

        <div class="bg-gray-900 border border-green-500/30 rounded-lg p-8 shadow-[0_0_30px_rgba(74,222,128,0.1)]">
            <!-- Success Message -->
            <div class="mb-8 pb-6 border-b border-green-500/20">
                <div class="flex items-center gap-2 text-green-400 font-mono text-xs mb-4">
                    <i class="fas fa-terminal"></i>
                    SYSTEM_RESPONSE
                </div>
                <p class="text-gray-300 font-mono">
                    > User <span class="text-cyan-400">{{ $inscription->user->prenom }}</span> successfully registered<br>
                    > Confirmation email sent to <span class="text-purple-400">{{ $inscription->user->email }}</span><br>
                    > Status: <span class="text-green-400 animate-pulse">CONFIRMED</span>
                </p>
            </div>

            <!-- Event Details -->
            <div class="mb-8 pb-6 border-b border-cyan-500/20">
                <div class="flex items-center gap-2 text-cyan-400 font-mono text-xs mb-4">
                    <i class="fas fa-database"></i>
                    EVENT_DATA
                </div>
                <div class="bg-black/50 rounded p-4 font-mono text-sm">
                    <div class="grid grid-cols-[100px_1fr] gap-2">
                        <span class="text-gray-500">name:</span>
                        <span class="text-white">{{ $inscription->evenement->titre }}</span>
                        
                        <span class="text-gray-500">date:</span>
                        <span class="text-white">{{ $inscription->evenement->date_heure_debut->format('Y-m-d H:i') }}</span>
                        
                        <span class="text-gray-500">location:</span>
                        <span class="text-white">{{ $inscription->evenement->lieu }}</span>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div class="flex items-center gap-6 mb-8">
                @if($inscription->photo)
                    <img src="{{ asset('storage/' . $inscription->photo) }}" 
                         alt="{{ $inscription->user->prenom }}"
                         class="w-24 h-24 rounded border-2 border-cyan-400 object-cover shadow-[0_0_20px_rgba(34,211,238,0.3)]">
                @else
                    <div class="w-24 h-24 rounded border-2 border-cyan-400 flex items-center justify-center bg-black shadow-[0_0_20px_rgba(34,211,238,0.3)]">
                        <i class="fas fa-user text-cyan-400 text-3xl"></i>
                    </div>
                @endif
                
                <div class="font-mono">
                    <div class="text-white text-lg mb-1">
                        {{ $inscription->user->prenom }} {{ $inscription->user->nom }}
                    </div>
                    <div class="text-cyan-400 text-sm">{{ $inscription->user->email }}</div>
                    @if($inscription->company)
                        <div class="text-purple-400 text-xs mt-1">
                            {{ $inscription->poste }} @ {{ $inscription->company }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- QR Code Placeholder -->
            <div class="bg-black/50 rounded p-6 text-center border border-dashed border-gray-700">
                <i class="fas fa-qrcode text-6xl text-gray-700 mb-4"></i>
                <p class="text-gray-500 text-xs font-mono">Your access QR code will be generated shortly</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
            <a href="{{ route('public.evenement.landing', $inscription->evenement) }}"
               class="block bg-cyan-400 text-black text-center py-4 rounded font-bold font-mono hover:bg-cyan-300 transition shadow-[0_0_20px_rgba(34,211,238,0.3)]">
                <i class="fas fa-arrow-left mr-2"></i>RETURN_TO_EVENT()
            </a>
            <button onclick="window.print()"
                    class="block border border-purple-400 text-purple-400 text-center py-4 rounded font-bold font-mono hover:bg-purple-400 hover:text-black transition">
                <i class="fas fa-print mr-2"></i>PRINT_TICKET()
            </button>
        </div>

        <div class="mt-8 text-center">
            <button onclick="if(confirm('Cancel registration?')) { document.getElementById('cancel-form').submit(); }" 
                    class="text-red-400/60 hover:text-red-400 transition text-xs font-mono uppercase">
                [x] abort_registration
            </button>
            <form id="cancel-form" action="{{ route('inscription.cancel', $inscription) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</section>
@endsection
