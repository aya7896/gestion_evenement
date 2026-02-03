<header class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-800/50 shadow-sm">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Toggle sidebar + Logo + titre -->
            <div class="flex items-center gap-4">
                <!-- Toggle sidebar button -->
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="p-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200 group">
                    <svg class="w-6 h-6 text-slate-600 dark:text-slate-400 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 via-rose-500 to-pink-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-orange-500/30">
                        GES
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white hidden sm:block">
                        Gestion  <span class="text-orange-600">Events</span>
                    </span>
                </div>
            </div>

            <!-- Droite : toggle dark + user + logout -->
            <div class="flex items-center gap-3 sm:gap-5">

                <!-- Dark mode toggle -->
                <button @click="darkMode = !darkMode" 
                        class="p-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200 group relative">
                    <!-- Sun icon -->
                    <svg x-show="!darkMode" class="w-5 h-5 text-slate-600 group-hover:text-orange-600 transition-colors" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon icon -->
                    <svg x-show="darkMode" class="w-5 h-5 text-slate-400 group-hover:text-orange-400 transition-colors" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <!-- User info -->
                <div class="hidden md:flex items-center gap-3 px-3 py-2 rounded-xl bg-slate-100/50 dark:bg-slate-800/50">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500 to-rose-600 flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom ?? 'S', 0, 1)) }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 leading-tight">
                            {{ auth()->user()->prenom ?? '' }} {{ auth()->user()->nom ?? '' }}
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 capitalize leading-tight">
                            {{ str_replace('_', ' ', auth()->user()->role ?? 'utilisateur') }}
                        </span>
                    </div>
                </div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl text-sm font-medium text-slate-600 hover:text-white hover:bg-orange-600 dark:text-slate-400 dark:hover:text-white dark:hover:bg-orange-600 transition-all duration-200 border border-slate-200 dark:border-slate-700 hover:border-orange-600">
                        <span class="hidden sm:inline">Déconnexion</span>
                        <svg class="w-5 h-5 sm:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>