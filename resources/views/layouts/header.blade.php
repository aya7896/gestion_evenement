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

                <!-- Logo (entreprise) -->
                <div class="flex items-center gap-3">
                    @php
                        $collab = auth()->user()->collaborateurs()->first();
                        $entreprise = $collab ? $collab->entreprise : null;
                        $logoPath = $entreprise?->logo ?? null;
                        $logoExists = $logoPath ? \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath) : false;
                    @endphp
                    @if($logoExists)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($logoPath) }}" alt="{{ $entreprise->nom }}" class="w-12 h-12 rounded-full object-cover shadow-lg hidden sm:block">
                    @else
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 via-rose-500 to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            <i class="fas fa-building"></i>
                        </div>
                    @endif
                    <div class="flex items-center gap-3">
                        <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white hidden sm:block">
                            Gestion  <span class="text-orange-600">Events</span>
                        </span>
                        @if (auth()->user()->isSuperAdmin())
                            <span class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 dark:from-purple-900/40 dark:to-pink-900/40 dark:text-purple-300 border border-purple-200 dark:border-purple-800/30 shadow-sm">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Super Admin
                            </span>
                        @elseif (auth()->user()->isCollaborateur())
                            <span class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-gradient-to-r from-orange-100 to-rose-100 text-orange-800 dark:from-orange-900/40 dark:to-rose-900/40 dark:text-orange-300 border border-orange-200 dark:border-orange-800/30 shadow-sm">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                Gestionnaire
                            </span>
                        @endif
                    </div>
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