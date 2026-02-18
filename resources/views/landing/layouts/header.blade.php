<header class="fixed w-full top-0 z-50 glass-effect">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('public.evenement.landing', $evenement) }}" class="flex items-center space-x-3 hover:opacity-80 transition group">
                    <div class="w-12 h-12 gradient-bg rounded-xl flex items-center justify-center transform group-hover:rotate-12 transition duration-300">
                        <i class="fas fa-angle-double-right text-white text-2xl"></i>
                    </div>
                    <div>
                        <span class="text-2xl font-bold text-white tracking-wider block"> {{ $evenement->titre }}</span>
                        <span class="text-xs text-conf-light tracking-widest uppercase">Events Platform</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Desktop -->
            <div class="hidden lg:flex items-center space-x-8">
                <a href="{{ route('public.evenement.landing', $evenement) }}#home" class="nav-link text-white hover:text-conf-light transition font-medium text-sm uppercase tracking-wider">HOME</a>
                <a href="{{ route('public.evenement.landing', $evenement) }}#details" class="nav-link text-white hover:text-conf-light transition font-medium text-sm uppercase tracking-wider">Details</a>
                <a href="{{ route('public.evenement.landing', $evenement) }}#schedule" class="nav-link text-white hover:text-conf-light transition font-medium text-sm uppercase tracking-wider">Programme</a>
                <a href="{{ route('public.evenement.landing', $evenement) }}#speakers" class="nav-link text-white hover:text-conf-light transition font-medium text-sm uppercase tracking-wider">Speakers</a>
                <a href="{{ route('public.evenement.landing', $evenement) }}#sponsors" class="nav-link text-white hover:text-conf-light transition font-medium text-sm uppercase tracking-wider">Sponsors</a>

            </div>

            <!-- CTA Button -->
            <div class="hidden lg:block">
                <a href="{{ route('inscription.create', $evenement) }}" class="btn-gradient text-white px-8 py-3 rounded-full font-semibold flex items-center text-sm uppercase tracking-wider">
                    <i class="fas fa-ticket-alt mr-2"></i>
               REGISTER
                </a>
            </div>

            <!-- Menu Mobile -->
            <button class="lg:hidden text-white text-2xl p-2 hover:bg-white/10 rounded-lg transition" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden mt-4 pb-4 border-t border-white/10">
            <div class="flex flex-col space-y-2 pt-4">
                <a href="{{ route('public.evenement.landing', $evenement) }}#home" class="text-white hover:text-conf-light transition py-2">HOME</a>
                <a href="{{ route('public.evenement.landing', $evenement) }}#details" class="text-white hover:text-conf-light transition py-2">Details</a>
                <a href="{{ route('public.evenement.ateliers', $evenement) }}" class="text-white hover:text-conf-light transition py-2">Ateliers</a>
                <a href="{{ route('public.evenement.landing', $evenement) }}#schedule" class="text-white hover:text-conf-light transition py-2">Programme</a>
                <a href="{{ route('public.evenement.landing', $evenement) }}#speakers" class="text-white hover:text-conf-light transition py-2">Speakers</a>
                <a href="{{ route('public.evenement.landing', $evenement) }}#sponsors" class="text-white hover:text-conf-light transition py-2">Sponsors</a>
                <a href="{{ route('inscription.create', $evenement) }}" class="btn-gradient text-white px-6 py-3 rounded-full text-center font-semibold mt-4">
                    REGISTER NOW
                </a>
            </div>
        </div>
    </nav>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</header>