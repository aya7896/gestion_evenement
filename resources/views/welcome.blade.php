<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion Événements - Bienvenue</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script> <!-- Remplacez par votre kit FontAwesome -->

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-gray-100 dark:bg-slate-900 text-gray-800 dark:text-gray-200">
    <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-rose-500 selection:text-white">
        <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
            <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                <div class="flex lg:justify-center lg:col-start-2">
                    {{-- Logo ou titre de l'application --}}
                    <h1 class="text-4xl font-bold text-gradient-orange">
                        Gestion Événements
                    </h1>
                </div>
                @if (Route::has('login'))
                    <nav class="-mx-3 flex flex-1 justify-end">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Connexion
                            </a>

                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                >
                                    Inscription
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>

            <main class="mt-12 animate-fade-in-up">
                <div class="glass-card p-8 sm:p-12">
                    <div class="text-center">
                        <i class="fas fa-calendar-check text-6xl text-orange-500 mb-6"></i>
                        <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
                            Organisez vos événements avec simplicité
                        </h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                            Notre plateforme vous offre tous les outils nécessaires pour planifier, gérer et suivre vos événements, ateliers et collaborateurs en un seul endroit.
                        </p>
                        <div class="mt-8">
                            <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-orange-500 to-rose-500 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:scale-105 transform transition-transform duration-300">
                                Commencer
                            </a>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="py-16 text-center text-sm text-black dark:text-white/70">
               
        </div>
    </div>
</body>
</html>
