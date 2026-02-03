<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Atelier;
class AtelierSeeder extends Seeder
{
    public function run(): void
    {
        Atelier::create([
            'id_event' => 1,
            'titre' => 'Workshop IA',
            'visibility' => 'public',
            'heure_debut' => '09:00',
            'heure_fin' => '12:00',
            'sujet' => 'Intelligence Artificielle',
            'banniere' => null,
            'date' => now()->addDays(10),
            'image' => null,
            'capacite' => 40,
            'status' => 'confirmé',
            'online_link' => null,
        ]);
        Atelier::create([
            'id_event' => 2,
            'titre' => 'Design Sprint',
            'visibility' => 'privé',
            'heure_debut' => '14:00',
            'heure_fin' => '17:00',
            'sujet' => 'Sprint UX',
            'banniere' => null,
            'date' => now()->addDays(15),
            'image' => null,
            'capacite' => 20,
            'status' => 'en attente',
            'online_link' => null,
        ]);
    }
}
