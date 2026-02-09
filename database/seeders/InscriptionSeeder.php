<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Inscription;
use App\Models\Inscription_event;
use App\Models\Evenement;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class InscriptionSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer le premier événement
        $evenement = Evenement::first();
        
        if (!$evenement) {
            throw new \Exception('Aucun événement trouvé. Veuillez d\'abord exécuter les seeders d\'événements.');
        }

        // Créer 7 utilisateurs participants
        $participants = [
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'email' => 'jean.dupont@email.com',
                'telephone' => '0601234567',
                'company' => 'TechCorp',
                'poste' => 'Directeur IT',
            ],
            [
                'nom' => 'Martin',
                'prenom' => 'Marie',
                'email' => 'marie.martin@email.com',
                'telephone' => '0602345678',
                'company' => 'InnovateLabs',
                'poste' => 'Chef de Projet',
            ],
            [
                'nom' => 'Bernard',
                'prenom' => 'Pierre',
                'email' => 'pierre.bernard@email.com',
                'telephone' => '0603456789',
                'company' => 'DataSolutions',
                'poste' => 'Ingénieur Data',
            ],
            [
                'nom' => 'Petit',
                'prenom' => 'Sophie',
                'email' => 'sophie.petit@email.com',
                'telephone' => '0604567890',
                'company' => 'CloudFirst',
                'poste' => 'Architecte Cloud',
            ],
            [
                'nom' => 'Durand',
                'prenom' => 'Luc',
                'email' => 'luc.durand@email.com',
                'telephone' => '0605678901',
                'company' => 'SecureNet',
                'poste' => 'Responsable Sécurité',
            ],
            [
                'nom' => 'Moreau',
                'prenom' => 'Claire',
                'email' => 'claire.moreau@email.com',
                'telephone' => '0606789012',
                'company' => 'DevStudio',
                'poste' => 'Lead Developer',
            ],
            [
                'nom' => 'Lefebvre',
                'prenom' => 'Marc',
                'email' => 'marc.lefebvre@email.com',
                'telephone' => '0607890123',
                'company' => 'WebAgency',
                'poste' => 'Développeur Full-Stack',
            ],
        ];

        // Créer les utilisateurs et les inscriptions
        foreach ($participants as $index => $participantData) {
            // Créer ou récupérer l'utilisateur
            $user = User::firstOrCreate(
                ['email' => $participantData['email']],
                [
                    'nom' => $participantData['nom'],
                    'prenom' => $participantData['prenom'],
                    'telephone' => $participantData['telephone'],
                    'password' => Hash::make('password'),
                    'role' => 'participant',
                ]
            );

            // Créer l'inscription
            $inscription = Inscription::create([
                'id_user' => $user->id_user,
                'date_ins' => now()->subDays(rand(1, 10)),
                'company' => $participantData['company'],
                'photo' => null,
                'presentation' => 'Je suis intéressé par l\'innovation et les nouvelles technologies.',
                'poste' => $participantData['poste'],
                'lien_linkedin' => 'https://linkedin.com/in/' . strtolower($participantData['prenom'] . '-' . $participantData['nom']),
                'objectif' => 'Échanger avec les experts et découvrir les dernières tendances.',
                'statut' => $index < 3 ? 'validée' : 'en_attente', // Les 3 premiers sont validés
            ]);

            // Lier l'inscription à l'événement
            Inscription_event::create([
                'id_inscription' => $inscription->id_inscription,
                'id_event' => $evenement->id_event,
            ]);
        }

        $this->command->info('✅ 7 inscriptions de test créées avec succès !');
        $this->command->info('   - 3 inscriptions avec le statut "validée"');
        $this->command->info('   - 4 inscriptions avec le statut "en_attente"');
    }
}
