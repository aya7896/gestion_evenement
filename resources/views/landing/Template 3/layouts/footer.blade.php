{{-- resources/views/templates/tech/components/footer.blade.php --}}
<footer class="bg-black border-t border-cyan-500/30 py-12 font-mono">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-microchip text-cyan-400"></i>
                    <span class="font-bold text-lg tracking-widest">
                        NEO<span class="text-cyan-400">CONF</span>
                    </span>
                </div>
                <p class="text-gray-500 text-xs leading-relaxed">
                    // {{ Str::limit($evenement->description, 80) }}
                </p>
            </div>
            
            <div>
                <h3 class="text-cyan-400 text-xs uppercase tracking-widest mb-4">// SYSTEM.INFO</h3>
                <ul class="space-y-2 text-xs text-gray-400">
                    <li>Date: {{ $evenement->date_heure_debut?->format('Y-m-d H:i') ?? 'TBD' }}</li>
                    <li>Location: {{ $evenement->lieu ?? 'TBD' }}</li>
                    <li>Host: {{ $evenement->entreprise->nom ?? 'Unknown' }}</li>
                </ul>
            </div>

            <div>
                <h3 class="text-purple-400 text-xs uppercase tracking-widest mb-4">// NETWORK</h3>
                <div class="flex gap-4">
                    <a href="#" class="w-8 h-8 border border-cyan-500/30 flex items-center justify-center text-cyan-400 hover:bg-cyan-400 hover:text-black transition">
                        <i class="fab fa-github text-xs"></i>
                    </a>
                    <a href="#" class="w-8 h-8 border border-purple-500/30 flex items-center justify-center text-purple-400 hover:bg-purple-400 hover:text-black transition">
                        <i class="fab fa-discord text-xs"></i>
                    </a>
                    <a href="#" class="w-8 h-8 border border-green-500/30 flex items-center justify-center text-green-400 hover:bg-green-400 hover:text-black transition">
                        <i class="fab fa-twitter text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="border-t border-cyan-500/20 pt-8 text-center">
            <p class="text-gray-600 text-xs">
                &copy; {{ date('Y') }} {{ $evenement->entreprise->nom ?? 'NeoEvents' }} // SYSTEM_STATUS: <span class="text-green-400">ONLINE</span>
            </p>
        </div>
    </div>
</footer>