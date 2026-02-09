<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SuperAdmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Utilisateurs et SuperAdmin
        $superAdminUser = User::firstOrCreate([
            'email' => 'superadmin@example.com',
        ], [
            'nom' => 'Super',
            'prenom' => 'Admin',
            'telephone' => '0600000001',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);
        SuperAdmin::firstOrCreate([
            'id_user' => $superAdminUser->id_user,
        ]);
        User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'nom' => 'Admin',
            'prenom' => 'User',
            'telephone' => '0600000002',
            'password' => Hash::make('password'),
            'role' => 'collaborateur',
        ]);
        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'nom' => 'Test',
            'prenom' => 'User',
            'telephone' => '0600000000',
            'password' => Hash::make('password'),
            'role' => 'participant',
        ]);

        // Seed Entreprises, Collaborateurs de démo AVANT les événements
        $this->call([
            EntrepriseSeeder::class,
            EntrepriseDemoSeeder::class,
            AdminEntrepriseDemoSeeder::class,
            CollaborateurDemoSeeder::class,
            EvenementSeeder::class,
            AtelierSeeder::class,
            EvenementDemoSeeder::class,
            InscriptionSeeder::class,
        ]);
    }
}
