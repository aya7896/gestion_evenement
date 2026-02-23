{{-- resources/views/templates/tech/components/header.blade.php --}}
<header class="fixed w-full top-0 z-50 bg-black/80 backdrop-blur-md border-b border-cyan-500/30">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <a href="{{ route('public.evenement.landing', $evenement) }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 border border-cyan-400 rounded flex items-center justify-center shadow-[0_0_15px_rgba(34,211,238,0.3)]">
                    <i class="fas fa-microchip text-cyan-400"></i>
                </div>
                <span class="font-bold text-xl tracking-widest text-white font-mono">
                    NEO<span class="text-cyan-400">CONF</span><span class="text-xs align-top text-purple-400">_2024</span>
                </span>
            </a>
            
            <div class="hidden lg:flex items-center gap-8 font-mono text-sm">
                <a href="#about" class="text-gray-400 hover:text-cyan-400 transition flex items-center gap-2">
                    <span class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></span>
                    >_INFO
                </a>
                <a href="#agenda" class="text-gray-400 hover:text-cyan-400 transition">[AGENDA]</a>
                <a href="#speakers" class="text-gray-400 hover:text-cyan-400 transition">[SPEAKERS]</a>
                <a href="#sponsors" class="text-gray-400 hover:text-cyan-400 transition">[SPONSORS]</a>
            </div>

            <a href="{{ route('inscription.create', $evenement) }}" class="hidden lg:block border border-cyan-400 text-cyan-400 px-6 py-2 font-mono text-sm hover:bg-cyan-400 hover:text-black transition shadow-[0_0_15px_rgba(34,211,238,0.2)]">
                >_JOIN_NOW()
            </a>

            <button class="lg:hidden text-cyan-400 text-2xl font-mono" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                [MENU]
            </button>
        </div>

        <div id="mobile-menu" class="hidden lg:hidden mt-4 pb-4 border-t border-cyan-500/30 pt-4 font-mono">
            <div class="flex flex-col gap-3">
                <a href="#about" class="text-gray-400 py-2">>_INFO</a>
                <a href="#agenda" class="text-gray-400 py-2">[AGENDA]</a>
                <a href="#speakers" class="text-gray-400 py-2">[SPEAKERS]</a>
                <a href="{{ route('inscription.create', $evenement) }}" class="border border-cyan-400 text-cyan-400 px-6 py-3 text-center mt-2">
                    >_JOIN_NOW()
                </a>
            </div>
        </div>
    </nav>
</header>