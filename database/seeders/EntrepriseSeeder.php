<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Entreprise;
class EntrepriseSeeder extends Seeder
{
    public function run(): void
    {
        Entreprise::create([
            'nom' => 'Tech Innov',
            'logo' => null,
            'site_web' => 'https://techinnov.com',
            'email' => 'contact@techinnov.com',
            'tel' => '0520000001',
            'description' => 'Entreprise innovante dans la tech.',
            'adresse' => '123 Bd Hassan II',
            'ville' => 'Casablanca',
            'secteur_activite' => 'Technologie',
            'taille_entreprise' => 'moyenne',
            'status' => 'active',
        ]);
        Entreprise::create([
            'nom' => 'DesignPro',
            'logo' => null,
            'site_web' => 'https://designpro.com',
            'email' => 'info@designpro.com',
            'tel' => '0520000002',
            'description' => 'Agence de design et communication.',
            'adresse' => '456 Rue Zerktouni',
            'ville' => 'Rabat',
            'secteur_activite' => 'Design',
            'taille_entreprise' => 'petite',
            'status' => 'active',
        ]);
    }
}
