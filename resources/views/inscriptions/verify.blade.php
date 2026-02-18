@extends('landing.layouts.app')

@section('title', 'Verification inscription')

@section('content')
<section class="min-h-screen py-20 gradient-bg-dark">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="bg-white/10 border border-white/20 rounded-2xl p-8 text-white">
            <h1 class="text-3xl font-bold mb-4">Verifier votre inscription</h1>
            <p class="text-gray-300 mb-6">
                Un code de 6 chiffres a ete envoye a <strong>{{ $inscription->user->email }}</strong>.
                Le code expire dans 2 minutes.
            </p>

            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-500/20 border border-green-400 text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 rounded bg-red-500/20 border border-red-400 text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 rounded bg-red-500/20 border border-red-400 text-red-200">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('inscription.verify.submit', $inscription) }}" class="space-y-4">
                @csrf
                <input
                    type="text"
                    name="code"
                    maxlength="6"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    placeholder="Entrez le code"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/30 text-white tracking-[0.35em] text-center text-2xl"
                >
                <button type="submit" class="w-full btn-gradient text-white py-3 rounded-full font-semibold">
                    Verifier mon inscription
                </button>
            </form>

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <form method="POST" action="{{ route('inscription.verify.resend', $inscription) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 rounded-lg border border-white/30 hover:bg-white/10 transition">
                        Renvoyer le code par email
                    </button>
                </form>
                <form method="POST" action="{{ route('inscription.verify.sms', $inscription) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 rounded-lg border border-white/30 hover:bg-white/10 transition">
                        Essayer autre methode (SMS)
                    </button>
                </form>
            </div>

            @if($inscription->verification_expires_at)
                <p class="mt-6 text-sm text-gray-300">
                    Expire dans: <span id="otp-countdown" data-expiry="{{ $inscription->verification_expires_at->toIso8601String() }}">--:--</span>
                </p>
            @endif
        </div>
    </div>
</section>

<script>
    (function () {
        const el = document.getElementById('otp-countdown');
        if (!el) return;
        const expiry = new Date(el.dataset.expiry).getTime();
        const tick = () => {
            const diff = expiry - Date.now();
            if (diff <= 0) {
                el.textContent = '00:00 (expire)';
                return;
            }
            const totalSec = Math.floor(diff / 1000);
            const min = String(Math.floor(totalSec / 60)).padStart(2, '0');
            const sec = String(totalSec % 60).padStart(2, '0');
            el.textContent = min + ':' + sec;
            setTimeout(tick, 1000);
        };
        tick();
    })();
</script>
@endsection
