<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminEntrepriseDemoSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate([
            'id_user' => 100
        ], [
            'nom' => 'Admin',
            'prenom' => 'Entreprise',
            'email' => 'admin-entreprise-demo@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin_entreprise',
        ]);
    }
}
