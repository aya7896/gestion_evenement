<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evenement;
use Carbon\Carbon;

class EvenementDemoSeeder extends Seeder
{
    public function run()
    {
        Evenement::updateOrCreate([
            'id_event' => 100
        ], [
            'id_Collaborateur' => 100,
            'id_entreprise' => 100,
            'titre' => 'Événement Démo',
            'capacite' => 50,
            'description' => 'Un événement de démonstration pour tests.',
            'type' => 'presentiel',
            'localisation' => 'Paris',
            'lieu' => 'Salle Demo',
            'date_heure_debut' => Carbon::now()->addDays(2),
            'date_heure_fin' => Carbon::now()->addDays(2)->addHours(2),
            'mode' => 'présentiel',
            'status' => 'published',
            'visibility' => 'publique',
        ]);
    }
}
