<footer class="bg-[#0a0318] border-t border-conf-primary/30 py-10">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div>
                <h3 class="gradient-text text-base font-semibold mb-3">Event</h3>
                <p class="text-gray-200 font-medium">{{ $evenement->titre }}</p>
                <p class="text-gray-400 text-sm mt-2">
                    {{ $evenement->lieu ?? 'Lieu non renseigne' }}
                    @if(!empty($evenement->localisation))
                        - {{ $evenement->localisation }}
                    @endif
                </p>
                <p class="text-gray-400 text-sm mt-1">
                    {{ $evenement->date_heure_debut?->format('d/m/Y H:i') ?? '-' }}
                    @if(!empty($evenement->date_heure_fin))
                        -> {{ $evenement->date_heure_fin?->format('d/m/Y H:i') }}
                    @endif
                </p>
            </div>

            <div>
                <h3 class="gradient-text text-base font-semibold mb-3">Host Company</h3>
                <p class="text-gray-200 font-medium">{{ $evenement->entreprise->nom ?? 'Entreprise hote' }}</p>
                @if(!empty($evenement->entreprise?->secteur_activite))
                    <p class="text-gray-400 text-sm mt-1">{{ $evenement->entreprise->secteur_activite }}</p>
                @endif
                <p class="text-gray-400 text-sm mt-1">
                    {{ $evenement->entreprise?->adresse ?? '' }}
                    @if(!empty($evenement->entreprise?->ville))
                        {{ $evenement->entreprise?->adresse ? ',' : '' }} {{ $evenement->entreprise?->ville }}
                    @endif
                </p>
            </div>

            <div>
                <h3 class="gradient-text text-base font-semibold mb-3">Contact</h3>
                <div class="space-y-1 text-sm">
                    <p class="text-gray-300">Email: {{ $evenement->entreprise->email ?? 'Non renseigne' }}</p>
                    <p class="text-gray-300">Telephone: {{ $evenement->entreprise->tel ?? 'Non renseigne' }}</p>
                    @if(!empty($evenement->entreprise?->site_web))
                        <p>
                            <a href="{{ $evenement->entreprise->site_web }}" target="_blank" rel="noopener noreferrer" class="text-gray-300 hover:text-conf-light transition">
                                Site web: {{ $evenement->entreprise->site_web }}
                            </a>
                        </p>
                    @endif
                </div>
                <nav class="flex flex-wrap gap-4 text-sm mt-4">
                    <a href="{{ route('public.evenement.landing', $evenement) }}#home" class="text-gray-400 hover:text-conf-light transition">Home</a>
                    <a href="{{ route('public.evenement.ateliers', $evenement) }}" class="text-gray-400 hover:text-conf-light transition">Workshops</a>
                    <a href="{{ route('inscription.create', $evenement) }}" class="text-gray-400 hover:text-conf-light transition">Registration</a>
                </nav>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-conf-primary/20 text-xs text-gray-500">
            &copy; {{ date('Y') }} {{ $evenement->entreprise->nom ?? 'Events Platform' }}. All rights reserved.
        </div>
    </div>
</footer>