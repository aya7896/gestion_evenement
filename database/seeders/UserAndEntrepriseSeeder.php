<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserAndEntrepriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    
    {
        // Insert users
        DB::table('users')->insert([
            [
                'nom' => 'Super',
                'prenom' => 'Admin',
                'email' => 'superadmin@test.com',
                'telephone' => '0600000000',
                'password' => '$2y$12$8d.o3jE1D4zmptbQ.qNfTeWIV6Q9wcaNWCY2adpFaFHg65G8hpMCm',
                'role' => 'super_admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Alice',
                'prenom' => 'Admin',
                'email' => 'alice@entreprise.com',
                'telephone' => '0611111111',
                'password' => '$2y$12$8d.o3jE1D4zmptbQ.qNfTeWIV6Q9wcaNWCY2adpFaFHg65G8hpMCm',
                'role' => 'collaborateur',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Bob',
                'prenom' => 'Scanner',
                'email' => 'bob@entreprise.com',
                'telephone' => '0622222222',
                'password' => '$2y$12$8d.o3jE1D4zmptbQ.qNfTeWIV6Q9wcaNWCY2adpFaFHg65G8hpMCm',
                'role' => 'collaborateur',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Charlie',
                'prenom' => 'Participant',
                'email' => 'charlie@test.com',
                'telephone' => '0633333333',
                'password' => '$2y$12$8d.o3jE1D4zmptbQ.qNfTeWIV6Q9wcaNWCY2adpFaFHg65G8hpMCm',
                'role' => 'participant',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Insert entreprise
        DB::table('entreprises')->insert([
            [
                'nom' => 'Entreprise Test',
                'ville' => 'Casablanca',
                'secteur_activite' => 'Informatique',
                'taille_entreprise' => 'moyenne',
                'adresse' => '123 rue exemple',
                'email' => 'contact@entreprise.com',
                'tel' => '0644444444',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // Insert collaborateurs
        DB::table('collaborateurs')->insert([
            [
                'id_user' => 2, // Replace with actual user ID for Alice
                'id_entreprise' => 1, // Replace with actual entreprise ID
                'role' => 'admin_entreprise',
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_user' => 3, // Replace with actual user ID for Bob
                'id_entreprise' => 1, // Replace with actual entreprise ID
                'role' => 'scanner',
                'active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
