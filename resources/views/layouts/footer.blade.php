<footer class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-t border-slate-200/50 dark:border-slate-800/50 mt-auto transition-all duration-300"
        :class="sidebarOpen ? 'lg:ml-72' : 'lg:ml-20'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Copyright -->
            <div class="text-center md:text-left">
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    © {{ date('Y') }} <span class="font-semibold text-slate-900 dark:text-white">Gestion Events </span>
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">
                    Casablanca, Maroc • Tous droits réservés
                </p>
            </div>

            <!-- Links -->
            <div class="flex items-center gap-6">
                <a href="#" class="text-sm text-slate-600 dark:text-slate-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">
                    À propos
                </a>
                <a href="#" class="text-sm text-slate-600 dark:text-slate-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">
                    Support
                </a>
                <a href="#" class="text-sm text-slate-600 dark:text-slate-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors">
                    Confidentialité
                </a>
            </div>

            <!-- Version -->
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                    
                </span>
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse" title="Système opérationnel"></div>
            </div>
        </div>
    </div>
</footer>