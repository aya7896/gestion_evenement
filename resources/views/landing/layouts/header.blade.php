<header class="bg-white shadow-md sticky top-0 z-50">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('public.evenement.landing', $evenement->id_event) }}" class="flex items-center space-x-2 hover:opacity-80 transition">
                    <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                    <span class="text-xl font-bold text-gray-800">Events Platform</span>
                </a>
            </div>

            <!-- Navigation Desktop -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="#details" class="text-gray-700 hover:text-blue-600 transition">Détails</a>
                <a href="{{ route('public.evenement.ateliers', $evenement->id_event) }}" class="text-gray-700 hover:text-blue-600 transition">Ateliers</a>
                <a href="{{ route('inscription.create', $evenement->id_event) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    S'inscrire
                </a>
            </div>

            <!-- Menu Mobile -->
            <button class="md:hidden text-gray-700" onclick="toggleMobileMenu()">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden mt-4 space-y-2">
            <a href="#details" class="block py-2 text-gray-700 hover:text-blue-600">Détails</a>
            <a href="{{ route('public.evenement.ateliers', $evenement->id_event) }}" class="block py-2 text-gray-700 hover:text-blue-600">Ateliers</a>
            <a href="{{ route('inscription.create', $evenement->id_event) }}" class="block py-2 text-blue-600 font-semibold hover:text-blue-700">S'inscrire</a>
        </div>
    </nav>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</header>