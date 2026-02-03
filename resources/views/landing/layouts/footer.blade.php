<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            <!-- À propos -->
            <div>
                <h3 class="text-lg font-bold mb-4">À propos</h3>
                <p class="text-gray-400 text-sm">
                    Plateforme de gestion et de découverte d'événements professionnels et ateliers.
                </p>
            </div>

            <!-- Liens rapides -->
            <div>
                <h3 class="text-lg font-bold mb-4">Liens rapides</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Accueil</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Événements</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Contact</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-lg font-bold mb-4">Contact</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><i class="fas fa-envelope mr-2"></i> contact@events.com</li>
                    <li><i class="fas fa-phone mr-2"></i> +212 6XX XX XX XX</li>
                    <li><i class="fas fa-map-marker-alt mr-2"></i> Casablanca, Maroc</li>
                </ul>
            </div>

            <!-- Réseaux sociaux -->
            <div>
                <h3 class="text-lg font-bold mb-4">Suivez-nous</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition text-xl">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition text-xl">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition text-xl">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition text-xl">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} Events Platform. Tous droits réservés.</p>
        </div>
    </div>
</footer>