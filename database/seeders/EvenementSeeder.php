<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evenement;
use Carbon\Carbon;

class EvenementSeeder extends Seeder
{
    public function run(): void
    {
        // Événements pour les 6 derniers mois avec des données variées
        $months = [
            ['month' => -5, 'count' => 8, 'baseCapacity' => 50],
            ['month' => -4, 'count' => 12, 'baseCapacity' => 75],
            ['month' => -3, 'count' => 15, 'baseCapacity' => 100],
            ['month' => -2, 'count' => 18, 'baseCapacity' => 120],
            ['month' => -1, 'count' => 22, 'baseCapacity' => 150],
            ['month' => 0, 'count' => 25, 'baseCapacity' => 200],
        ];

        // Attendre que les entreprises et collaborateurs soient créés
        // Pour l'instant, utiliser des IDs fixes car les collaborateurs ne sont pas encore créés
        // Mais vérifier que les IDs existent dans la base de données
        $collaborateurs = \App\Models\Collaborateur::pluck('id_Collaborateur')->toArray();
        $entreprises = \App\Models\Entreprise::pluck('id_entreprise')->toArray();
        
        // Si les tableaux sont vides, utiliser des IDs par défaut
        if (empty($collaborateurs)) {
            $collaborateurs = [1, 2, 3, 4, 5];
        }
        if (empty($entreprises)) {
            $entreprises = [1, 2, 3, 4, 5];
        }
        
        // Vérifier que les IDs existent réellement dans la base de données
        // Si non, utiliser des IDs par défaut
        if (!empty($collaborateurs)) {
            $existingCollaborateurs = \App\Models\Collaborateur::whereIn('id_Collaborateur', $collaborateurs)->pluck('id_Collaborateur')->toArray();
            if (empty($existingCollaborateurs)) {
                $collaborateurs = [1, 2, 3, 4, 5];
            } else {
                $collaborateurs = $existingCollaborateurs;
            }
        } else {
            $collaborateurs = [1, 2, 3, 4, 5];
        }
        
        if (!empty($entreprises)) {
            $existingEntreprises = \App\Models\Entreprise::whereIn('id_entreprise', $entreprises)->pluck('id_entreprise')->toArray();
            if (empty($existingEntreprises)) {
                $entreprises = [1, 2, 3, 4, 5];
            } else {
                $entreprises = $existingEntreprises;
            }
        } else {
            $entreprises = [1, 2, 3, 4, 5];
        }
        
        // Vérifier que les IDs existent réellement dans la base de données
        // Si non, utiliser des IDs par défaut
        if (!empty($collaborateurs)) {
            $existingCollaborateurs = \App\Models\Collaborateur::whereIn('id_Collaborateur', $collaborateurs)->pluck('id_Collaborateur')->toArray();
            if (empty($existingCollaborateurs)) {
                $collaborateurs = [1, 2, 3, 4, 5];
            } else {
                $collaborateurs = $existingCollaborateurs;
            }
        } else {
            $collaborateurs = [1, 2, 3, 4, 5];
        }
        
        if (!empty($entreprises)) {
            $existingEntreprises = \App\Models\Entreprise::whereIn('id_entreprise', $entreprises)->pluck('id_entreprise')->toArray();
            if (empty($existingEntreprises)) {
                $entreprises = [1, 2, 3, 4, 5];
            } else {
                $entreprises = $existingEntreprises;
            }
        } else {
            $entreprises = [1, 2, 3, 4, 5];
        }
        $types = ['Conférence', 'Atelier', 'Séminaire', 'Workshop', 'Formation'];
        $modes = ['présentiel', 'en ligne', 'hybride'];
        $statuses = ['confirmé', 'en attente', 'annulé'];
        $visibilities = ['public', 'privé'];

        foreach ($months as $monthData) {
            $monthStart = Carbon::now()->addMonths($monthData['month'])->startOfMonth();
            $monthEnd = Carbon::now()->addMonths($monthData['month'])->endOfMonth();
            $daysInMonth = $monthEnd->day;

            for ($i = 1; $i <= $monthData['count']; $i++) {
                // Générer une date aléatoire dans le mois
                $day = rand(1, $daysInMonth);
                $hour = rand(9, 17);
                $duration = rand(2, 6);
                
                $startDate = Carbon::create($monthStart->year, $monthStart->month, $day, $hour);
                $endDate = $startDate->copy()->addHours($duration);

                Evenement::create([
                    'id_Collaborateur' => $collaborateurs[array_rand($collaborateurs)],
                    'id_entreprise' => $entreprises[array_rand($entreprises)],
                    'titre' => $this->generateEventTitle($types[array_rand($types)], $monthData['month']),
                    'capacite' => $monthData['baseCapacity'] + rand(0, 100),
                    'description' => $this->generateDescription(),
                    'type' => $types[array_rand($types)],
                    'localisation' => $this->generateLocation(),
                    'lieu' => $this->generateVenue(),
                    'date_heure_debut' => $startDate,
                    'date_heure_fin' => $endDate,
                    'mode' => $modes[array_rand($modes)],
                    'plaquette_pdf' => null,
                    'validation_superAdmin' => true,
                    'status' => $statuses[array_rand($statuses)],
                    'visibility' => $visibilities[array_rand($visibilities)],
                    'event_link' => null,
                    'image' => null,
                    'created_at' => $startDate->copy()->subDays(rand(1, 30)),
                    'updated_at' => $startDate->copy()->subDays(rand(0, 5)),
                ]);
            }
        }

        // Ajouter quelques événements récents pour tester
        Evenement::create([
            'id_Collaborateur' => 1,
            'id_entreprise' => 1,
            'titre' => 'Conférence Tech 2026',
            'capacite' => 200,
            'description' => 'Conférence annuelle sur l’innovation technologique.',
            'type' => 'Conférence',
            'localisation' => 'Casablanca',
            'lieu' => 'Palais des Congrès',
            'date_heure_debut' => now()->addDays(10),
            'date_heure_fin' => now()->addDays(10)->addHours(4),
            'mode' => 'présentiel',
            'plaquette_pdf' => null,
            'validation_superAdmin' => true,
            'status' => 'confirmé',
            'visibility' => 'public',
            'event_link' => null,
            'image' => null,
        ]);
    }

    private function generateEventTitle($type, $monthOffset): string
    {
        $themes = [
            'Technologie', 'Innovation', 'Digital', 'Marketing', 'Finance',
            'Management', 'Développement', 'Design', 'Communication', 'Stratégie'
        ];
        $years = ['2024', '2025', '2026'];
        $seasons = ['Printemps', 'Été', 'Automne', 'Hiver'];
        
        $theme = $themes[array_rand($themes)];
        $year = $years[array_rand($years)];
        $season = $seasons[array_rand($seasons)];
        
        return "$type $theme $season $year";
    }

    private function generateDescription(): string
    {
        $descriptions = [
            'Événement professionnel pour échanger sur les dernières tendances du secteur.',
            'Atelier interactif avec des experts reconnus dans le domaine.',
            'Conférence inspirante avec des intervenants de renommée internationale.',
            'Formation pratique pour développer de nouvelles compétences.',
            'Rencontre networking pour élargir son réseau professionnel.',
            'Séminaire de formation continue pour les professionnels.',
            'Workshop intensif sur les outils et méthodologies modernes.',
            'Journée découverte des innovations technologiques.',
            'Forum d\'échange entre professionnels du secteur.',
            'Masterclass avec des leaders d\'opinion reconnus.'
        ];
        
        return $descriptions[array_rand($descriptions)];
    }

    private function generateLocation(): string
    {
        $cities = ['Casablanca', 'Rabat', 'Marrakech', 'Fès', 'Tanger', 'Agadir', 'Meknès', 'Oujda'];
        return $cities[array_rand($cities)];
    }

    private function generateVenue(): string
    {
        $venues = [
            'Palais des Congrès', 'Centre des Expositions', 'Hôtel de Ville', 'Université',
            'Espace de Coworking', 'Centre de Formation', 'Salle de Conférence', 'Auditorium',
            'Espace événementiel', 'Centre des Affaires', 'Institut Culturel', 'Maison des Entreprises'
        ];
        return $venues[array_rand($venues)];
    }
}
