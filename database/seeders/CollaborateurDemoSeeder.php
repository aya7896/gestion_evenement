<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collaborateur;

class CollaborateurDemoSeeder extends Seeder
{
    public function run()
    {
        Collaborateur::updateOrCreate([
            'id_Collaborateur' => 100
        ], [
            'id_user' => 100,
            'id_entreprise' => 100,
            'role' => 'admin_entreprise',
            'active' => true,
        ]);
    }
}
