{{-- resources/views/templates/corporate/components/header.blade.php --}}
@php
$themes = [
    'violet' => ['dark' => '#160755', 'primary' => '#482395', 'secondary' => '#925CC7', 'light' => '#BF8DDA', 'accent' => '#1F0A67'],
    'ocean' => ['dark' => '#062A3A', 'primary' => '#0D4D68', 'secondary' => '#1693A5', 'light' => '#7AD6E0', 'accent' => '#0A3B52'],
];
$themeKey = $evenement->color_template ?? 'violet';
$theme = $themes[$themeKey] ?? $themes['violet'];
@endphp
<header class="fixed w-full top-0 z-50 bg-white/10 backdrop-blur-md border-b border-white/20">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <a href="{{ route('public.evenement.landing', $evenement) }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center transform group-hover:rotate-12 transition duration-300">
                    <i class="fas fa-cube text-white"></i>
                </div>
                <div>
                    <span class="text-xl font-bold text-white tracking-wide">{{ $evenement->titre }}</span>
                </div>
            </a>
            
            <div class="hidden lg:flex items-center gap-8">
                <a href="#details" class="text-white/80 hover:text-white transition text-sm font-medium uppercase tracking-wider">À propos</a>
                <a href="#schedule" class="text-white/80 hover:text-white transition text-sm font-medium uppercase tracking-wider">Programme</a>
                <a href="#speakers" class="text-white/80 hover:text-white transition text-sm font-medium uppercase tracking-wider">Speakers</a>
                <a href="#sponsors" class="text-white/80 hover:text-white transition text-sm font-medium uppercase tracking-wider">Sponsors</a>
            </div>

            <a href="{{ route('inscription.create', $evenement) }}" class="hidden lg:flex bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2.5 rounded-full font-semibold hover:shadow-lg hover:shadow-purple-500/30 transition transform hover:-translate-y-0.5">
                S'inscrire
            </a>

            <button class="lg:hidden text-white text-2xl" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div id="mobile-menu" class="hidden lg:hidden mt-4 pb-4 border-t border-white/10 pt-4">
            <div class="flex flex-col gap-3">
                <a href="#details" class="text-white/80 py-2">À propos</a>
                <a href="#schedule" class="text-white/80 py-2">Programme</a>
                <a href="#speakers" class="text-white/80 py-2">Speakers</a>
                <a href="{{ route('inscription.create', $evenement) }}" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-full text-center font-semibold mt-2">
                    S'inscrire maintenant
                </a>
            </div>
        </div>
    </nav>
</header>