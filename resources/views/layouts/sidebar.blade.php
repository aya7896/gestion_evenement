<aside class="fixed inset-y-0 left-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-r border-slate-200/50 dark:border-slate-800/50 shadow-xl pt-16 overflow-y-auto transition-all duration-300"
       :class="sidebarOpen ? 'w-72' : 'w-20'"
       @mouseenter="if (!sidebarOpen) $el.querySelector('.sidebar-tooltip')?.classList.remove('hidden')"
       @mouseleave="if (!sidebarOpen) $el.querySelector('.sidebar-tooltip')?.classList.add('hidden')">
    
    <div class="p-4 flex flex-col h-full">

        <!-- Navigation -->
        <nav class="flex-1 space-y-2">
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-3.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-rose-50 dark:hover:from-slate-800 dark:hover:to-slate-800/70 transition-all duration-200 group relative {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-orange-50 to-rose-50 dark:from-slate-800 dark:to-slate-800/70 text-orange-700 dark:text-orange-400 font-semibold shadow-sm' : '' }}"
               x-data="{ tooltip: false }">
                <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <span class="ml-3 whitespace-nowrap transition-all duration-300" 
                      :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                    Dashboard
                </span>
                <!-- Tooltip for collapsed state -->
                <span x-show="!sidebarOpen" x-cloak
                      class="absolute left-full ml-2 px-3 py-2 bg-slate-900 dark:bg-slate-700 text-white text-sm rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                    Dashboard
                </span>
            </a>

            <!-- Événements -->
            @php
                $isAdminEntreprise = auth()->user()->collaborateurs()->first()?->role === 'admin_entreprise';
            @endphp
            @if($isAdminEntreprise)
                <a href="{{ route('admin.evenements.index') }}"
                   class="flex items-center px-4 py-3.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-rose-50 dark:hover:from-slate-800 dark:hover:to-slate-800/70 transition-all duration-200 group relative {{ str_contains(request()->path(), 'evenements') ? 'bg-gradient-to-r from-orange-50 to-rose-50 dark:from-slate-800 dark:to-slate-800/70 text-orange-700 dark:text-orange-400 font-semibold shadow-sm' : '' }}">
                    <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="ml-3 whitespace-nowrap transition-all duration-300" 
                          :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                        Événements
                    </span>
                    <span x-show="!sidebarOpen" x-cloak
                          class="absolute left-full ml-2 px-3 py-2 bg-slate-900 dark:bg-slate-700 text-white text-sm rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                        Événements
                    </span>
                </a>
                <!-- Bouton d'ajout d'événement -->
                <a href="{{ route('admin.evenements.create') }}"
                   class="flex items-center mt-2 px-4 py-3.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold shadow transition group relative">
                    <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center">
                        <i class="fas fa-plus"></i>
                    </div>
                    <span class="ml-3 whitespace-nowrap transition-all duration-300"
                          :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                        Ajouter un événement
                    </span>
                </a>
            @else
                <a href="{{ route('evenements.index') }}"
                   class="flex items-center px-4 py-3.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-rose-50 dark:hover:from-slate-800 dark:hover:to-slate-800/70 transition-all duration-200 group relative {{ str_contains(request()->path(), 'evenements') ? 'bg-gradient-to-r from-orange-50 to-rose-50 dark:from-slate-800 dark:to-slate-800/70 text-orange-700 dark:text-orange-400 font-semibold shadow-sm' : '' }}">
                    <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="ml-3 whitespace-nowrap transition-all duration-300" 
                          :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                        Événements
                    </span>
                    <span x-show="!sidebarOpen" x-cloak
                          class="absolute left-full ml-2 px-3 py-2 bg-slate-900 dark:bg-slate-700 text-white text-sm rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                        Événements
                    </span>
                </a>
                <!-- Bouton d'ajout d'événement -->
                <a href="{{ route('evenements.create') }}"
                   class="flex items-center mt-2 px-4 py-3.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold shadow transition group relative">
                    <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center">
                        <i class="fas fa-plus"></i>
                    </div>
                    <span class="ml-3 whitespace-nowrap transition-all duration-300"
                          :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                        Ajouter un événement
                    </span>
                </a>
            @endif

            <!-- Ateliers -->
            <a href="{{ route('ateliers.index') }}"
               class="flex items-center px-4 py-3.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-rose-50 dark:hover:from-slate-800 dark:hover:to-slate-800/70 transition-all duration-200 group relative {{ str_contains(request()->path(), 'ateliers') ? 'bg-gradient-to-r from-orange-50 to-rose-50 dark:from-slate-800 dark:to-slate-800/70 text-orange-700 dark:text-orange-400 font-semibold shadow-sm' : '' }}">
                <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="ml-3 whitespace-nowrap transition-all duration-300" 
                      :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                    Ateliers
                </span>
                <span x-show="!sidebarOpen" x-cloak
                      class="absolute left-full ml-2 px-3 py-2 bg-slate-900 dark:bg-slate-700 text-white text-sm rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                    Ateliers
                </span>
            </a>

            @if (auth()->user()->isSuperAdmin())
            <!-- Entreprises (Super Admin only) -->
            <a href="{{ route('admin.entreprises.index') }}"
               class="flex items-center px-4 py-3.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-rose-50 dark:hover:from-slate-800 dark:hover:to-slate-800/70 transition-all duration-200 group relative {{ str_contains(request()->path(), 'entreprises') ? 'bg-gradient-to-r from-orange-50 to-rose-50 dark:from-slate-800 dark:to-slate-800/70 text-orange-700 dark:text-orange-400 font-semibold shadow-sm' : '' }}">
                <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                    </svg>
                </div>
                <span class="ml-3 whitespace-nowrap transition-all duration-300" 
                      :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                    Entreprises
                </span>
                <span x-show="!sidebarOpen" x-cloak
                      class="absolute left-full ml-2 px-3 py-2 bg-slate-900 dark:bg-slate-700 text-white text-sm rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                    Entreprises
                </span>
            </a>
            @endif

                @if (auth()->user()->isSuperAdmin())
                    <!-- Collaborateurs (Super Admin) -->
                    <a href="{{ route('admin.collaborateurs.index') }}" 
                       class="flex items-center px-4 py-3.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-rose-50 dark:hover:from-slate-800 dark:hover:to-slate-800/70 transition-all duration-200 group relative {{ request()->routeIs('admin.collaborateurs.*') ? 'bg-gradient-to-r from-orange-50 to-rose-50 dark:from-slate-800 dark:to-slate-800/70 text-orange-700 dark:text-orange-400 font-semibold shadow-sm' : '' }}">
                        <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <span class="ml-3 whitespace-nowrap transition-all duration-300" 
                              :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                            Collaborateurs
                        </span>
                        <span x-show="!sidebarOpen" x-cloak
                              class="absolute left-full ml-2 px-3 py-2 bg-slate-900 dark:bg-slate-700 text-white text-sm rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                            Collaborateurs
                        </span>
                    </a>
                @else
                    <!-- Mon Équipe (Admin Entreprise) -->
                    <a href="{{ route('admin.equipe.index') }}" 
                       class="flex items-center px-4 py-3.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-rose-50 dark:hover:from-slate-800 dark:hover:to-slate-800/70 transition-all duration-200 group relative {{ request()->routeIs('admin.equipe.*') ? 'bg-gradient-to-r from-orange-50 to-rose-50 dark:from-slate-800 dark:to-slate-800/70 text-orange-700 dark:text-orange-400 font-semibold shadow-sm' : '' }}">
                        <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <span class="ml-3 whitespace-nowrap transition-all duration-300" 
                              :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                            Mon Équipe
                        </span>
                        <span x-show="!sidebarOpen" x-cloak
                              class="absolute left-full ml-2 px-3 py-2 bg-slate-900 dark:bg-slate-700 text-white text-sm rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                            Mon Équipe
                        </span>
                    </a>
                    <!-- Infos Entreprise (Admin Entreprise) -->
                    <a href="{{ route('admin.entreprises.infos') }}" 
                       class="flex items-center px-4 py-3.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-rose-50 dark:hover:from-slate-800 dark:hover:to-slate-800/70 transition-all duration-200 group relative {{ request()->routeIs('admin.entreprises.infos') ? 'bg-gradient-to-r from-orange-50 to-rose-50 dark:from-slate-800 dark:to-slate-800/70 text-orange-700 dark:text-orange-400 font-semibold shadow-sm' : '' }}">
                        <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5"></path>
                            </svg>
                        </div>
                        <span class="ml-3 whitespace-nowrap transition-all duration-300" 
                              :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                            Infos Entreprise
                        </span>
                        <span x-show="!sidebarOpen" x-cloak
                              class="absolute left-full ml-2 px-3 py-2 bg-slate-900 dark:bg-slate-700 text-white text-sm rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                            Infos Entreprise
                        </span>
                    </a>
                @endif

            <!-- Divider -->
            <div class="my-4 border-t border-slate-200 dark:border-slate-800/50"></div>

            <!-- Analytics -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-3.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-rose-50 dark:hover:from-slate-800 dark:hover:to-slate-800/70 transition-all duration-200 group relative">
                <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="ml-3 whitespace-nowrap transition-all duration-300" 
                      :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                    Analytics
                </span>
                <span x-show="!sidebarOpen" x-cloak
                      class="absolute left-full ml-2 px-3 py-2 bg-slate-900 dark:bg-slate-700 text-white text-sm rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                    Analytics
                </span>
            </a>

            <!-- Settings -->
            <a href="#"
               class="flex items-center px-4 py-3.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-rose-50 dark:hover:from-slate-800 dark:hover:to-slate-800/70 transition-all duration-200 group relative">
                <div class="w-6 h-6 flex-shrink-0 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="ml-3 whitespace-nowrap transition-all duration-300" 
                      :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                    Paramètres
                </span>
                <span x-show="!sidebarOpen" x-cloak
                      class="absolute left-full ml-2 px-3 py-2 bg-slate-900 dark:bg-slate-700 text-white text-sm rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                    Paramètres
                </span>
            </a>
        </nav>

        <!-- Bas de sidebar -->
        <div class="mt-auto pt-6 border-t border-slate-200 dark:border-slate-800/50 transition-all duration-300">
            <div class="flex items-center justify-center" :class="sidebarOpen ? 'px-4' : 'px-2'">
                <div class="text-center" x-show="sidebarOpen" x-transition>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        © {{ date('Y') }} • GES
                    </p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                       
                    </p>
                </div>
                <div x-show="!sidebarOpen" class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
            </div>
        </div>
    </div>
</aside>