{{-- resources/views/templates/corporate/components/footer.blade.php --}}
<footer class="bg-slate-900 border-t border-white/10 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-cube text-white text-sm"></i>
                    </div>
                    <span class="text-lg font-bold text-white">{{ $evenement->titre }}</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    {{ Str::limit($evenement->description, 120) }}
                </p>
            </div>
            
            <div>
                <h3 class="text-white font-semibold mb-4">Informations</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li class="flex items-center gap-2">
                        <i class="far fa-calendar text-purple-400"></i>
                        {{ $evenement->date_heure_debut?->format('d/m/Y H:i') ?? 'Date à définir' }}
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-purple-400"></i>
                        {{ $evenement->lieu ?? 'Lieu à définir' }}
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="far fa-building text-purple-400"></i>
                        {{ $evenement->entreprise->nom ?? 'Organisateur' }}
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-white font-semibold mb-4">Contact</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li>{{ $evenement->entreprise->email ?? 'contact@evenement.com' }}</li>
                    <li>{{ $evenement->entreprise->tel ?? '' }}</li>
                    @if($evenement->entreprise?->site_web)
                    <li>
                        <a href="{{ $evenement->entreprise->site_web }}" target="_blank" class="text-purple-400 hover:text-purple-300 transition">
                            {{ $evenement->entreprise->site_web }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        
        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} {{ $evenement->entreprise->nom ?? 'Events Platform' }}. Tous droits réservés.</p>
            <div class="flex gap-4">
                <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-purple-500 hover:text-white transition">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-purple-500 hover:text-white transition">
                    <i class="fab fa-twitter"></i>
                </a>
            </div>
        </div>
    </div>
</footer>