<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entreprise;

class EntrepriseDemoSeeder extends Seeder
{
    public function run()
    {
        Entreprise::updateOrCreate([
            'id_entreprise' => 100
        ], [
            'nom' => 'Entreprise Démo',
            'secteur_activite' => 'Informatique',
            'ville' => 'Paris',
        ]);
    }
}
