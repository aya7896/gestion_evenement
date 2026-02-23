{{-- resources/views/templates/luxury/components/footer.blade.php --}}
<footer class="bg-black border-t border-yellow-500/30 py-16">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <div class="text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3 mb-6">
                    <div class="w-10 h-10 border border-yellow-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-crown text-yellow-500 text-sm"></i>
                    </div>
                    <span class="text-xl font-bold text-white tracking-widest uppercase font-serif">{{ $evenement->titre }}</span>
                </div>
                <p class="text-gray-400 font-serif leading-relaxed text-sm">
                    {{ Str::limit($evenement->description, 100) }}
                </p>
            </div>
            
            <div class="text-center">
                <h3 class="text-yellow-500 font-serif uppercase tracking-[0.3em] text-sm mb-6">Contact</h3>
                <p class="text-gray-300 font-serif text-sm mb-2">{{ $evenement->entreprise->nom ?? 'Organisateur' }}</p>
                <p class="text-gray-500 text-xs">{{ $evenement->entreprise->email ?? '' }}</p>
                <p class="text-gray-500 text-xs">{{ $evenement->entreprise->tel ?? '' }}</p>
            </div>

            <div class="text-center md:text-right">
                <h3 class="text-yellow-500 font-serif uppercase tracking-[0.3em] text-sm mb-6">Adresse</h3>
                <p class="text-gray-300 font-serif text-sm">{{ $evenement->lieu ?? 'Lieu prestigieux' }}</p>
                <p class="text-gray-500 text-xs mt-2">{{ $evenement->localisation ?? '' }}</p>
            </div>
        </div>
        
        <div class="border-t border-yellow-500/20 pt-8 text-center">
            <p class="text-gray-600 text-xs font-serif tracking-widest uppercase">
                &copy; {{ date('Y') }} {{ $evenement->entreprise->nom ?? 'Events Platform' }} — Tous droits réservés
            </p>
        </div>
    </div>
</footer>