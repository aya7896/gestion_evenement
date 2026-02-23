{{-- resources/views/templates/luxury/components/header.blade.php --}}
<header class="fixed w-full top-0 z-50 bg-black/95 border-b border-yellow-500/30">
    <nav class="container mx-auto px-6 py-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('public.evenement.landing', $evenement) }}" class="flex items-center gap-4 group">
                <div class="w-12 h-12 border-2 border-yellow-500 rounded-full flex items-center justify-center group-hover:bg-yellow-500 transition duration-500">
                    <i class="fas fa-crown text-yellow-500 group-hover:text-black text-lg transition"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-widest uppercase font-serif">{{ $evenement->titre }}</h1>
                    <p class="text-xs text-yellow-500 tracking-[0.4em] uppercase">Event</p>
                </div>
            </a>
            
            <div class="hidden lg:flex items-center gap-12">
                <a href="#details" class="text-white/80 hover:text-yellow-500 transition text-sm uppercase tracking-[0.2em] font-serif">Home</a>
                <a href="#programme" class="text-white/80 hover:text-yellow-500 transition text-sm uppercase tracking-[0.2em] font-serif">Programme</a>
                <a href="#intervenants" class="text-white/80 hover:text-yellow-500 transition text-sm uppercase tracking-[0.2em] font-serif">Speakers</a>
                  <a href="#intervenants" class="text-white/80 hover:text-yellow-500 transition text-sm uppercase tracking-[0.2em] font-serif">Sponsors</a>
            </div>

            <a href="{{ route('inscription.create', $evenement) }}" class="hidden lg:block border border-yellow-500 text-yellow-500 px-8 py-3 uppercase tracking-widest text-sm hover:bg-yellow-500 hover:text-black transition duration-300 font-serif">
                Register Now
            </a>

            <button class="lg:hidden text-yellow-500 text-2xl" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <div id="mobile-menu" class="hidden lg:hidden mt-6 pb-6 border-t border-yellow-500/30 pt-6">
            <div class="flex flex-col gap-4 text-center">
                <a href="#details" class="text-white py-2 uppercase tracking-widest">Le Gala</a>
                <a href="#programme" class="text-white py-2 uppercase tracking-widest">Programme</a>
                <a href="#intervenants" class="text-white py-2 uppercase tracking-widest">Intervenants</a>
                <a href="{{ route('inscription.create', $evenement) }}" class="border border-yellow-500 text-yellow-500 px-8 py-3 uppercase tracking-widest mt-4">
                    RSVP
                </a>
            </div>
        </div>
    </nav>
</header>