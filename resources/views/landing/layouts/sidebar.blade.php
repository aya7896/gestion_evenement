<aside class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
    
    <!-- Informations de l'événement -->
    <div class="mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            Informations
        </h3>
        
        <div class="space-y-4">
            <!-- Date et heure -->
            <div class="flex items-start">
                <i class="fas fa-calendar text-blue-600 mt-1 mr-3"></i>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Date</p>
                    <p class="text-sm text-gray-600">
                        {{ $evenement->date_heure_debut->format('d/m/Y') }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $evenement->date_heure_debut->format('H:i') }} - 
                        {{ $evenement->date_heure_fin->format('H:i') }}
                    </p>
                </div>
            </div>

            <!-- Lieu -->
            <div class="flex items-start">
                <i class="fas fa-map-marker-alt text-blue-600 mt-1 mr-3"></i>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Lieu</p>
                    <p class="text-sm text-gray-600">{{ $evenement->lieu }}</p>
                    <p class="text-xs text-gray-500">{{ $evenement->localisation }}</p>
                </div>
            </div>

            <!-- Mode -->
            <div class="flex items-start">
                <i class="fas fa-{{ $evenement->mode == 'online' ? 'laptop' : 'users' }} text-blue-600 mt-1 mr-3"></i>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Mode</p>
                    <p class="text-sm text-gray-600 capitalize">{{ $evenement->mode }}</p>
                </div>
            </div>

            <!-- Capacité -->
            <div class="flex items-start">
                <i class="fas fa-user-friends text-blue-600 mt-1 mr-3"></i>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Capacité</p>
                    <p class="text-sm text-gray-600">{{ $evenement->capacite }} participants</p>
                </div>
            </div>

            <!-- Type -->
            <div class="flex items-start">
                <i class="fas fa-tag text-blue-600 mt-1 mr-3"></i>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Type</p>
                    <p class="text-sm text-gray-600 capitalize">{{ $evenement->type }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton d'inscription -->
    <a href="#inscription" 
       class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-md">
        <i class="fas fa-user-plus mr-2"></i>
        S'inscrire maintenant
    </a>

    <!-- Statistiques -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <h4 class="text-sm font-bold text-gray-800 mb-3">
            <i class="fas fa-chart-line text-blue-600 mr-2"></i>
            Statistiques
        </h4>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-50 rounded-lg p-3 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $evenement->ateliers->count() }}</p>
                <p class="text-xs text-gray-600">Ateliers</p>
            </div>
            <div class="bg-green-50 rounded-lg p-3 text-center">
                <p class="text-2xl font-bold text-green-600">{{ $evenement->capacite }}</p>
                <p class="text-xs text-gray-600">Places</p>
            </div>
        </div>
    </div>

</aside>