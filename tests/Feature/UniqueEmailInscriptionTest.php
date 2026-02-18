<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Evenement;
use App\Models\Inscription;
use App\Models\Inscription_event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UniqueEmailInscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_register_same_email_twice_for_same_event()
    {
        // Créer un événement
        $evenement = Evenement::factory()->create();
        
        // Créer un utilisateur
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'role' => 'participant'
        ]);
        
        // Créer une inscription pour cet utilisateur
        $inscription = Inscription::factory()->create(['id_user' => $user->id_user]);
        Inscription_event::create([
            'id_inscription' => $inscription->id_inscription,
            'id_event' => $evenement->id_event,
            'id_user' => $user->id_user,
        ]);
        
        // Essayer de s'inscrire à nouveau avec le même email pour le même événement
        $response = $this->post(route('inscription.store', $evenement), [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'test@example.com',
            'telephone' => '0123456789',
            'password' => 'password123',
            'company' => 'Test Company',
        ]);
        
        // La validation doit échouer
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors(function ($errors) {
            return str_contains($errors->first('email'), 'déjà inscrit');
        });
    }

    public function test_can_register_same_email_for_different_events()
    {
        // Créer deux événements
        $evenement1 = Evenement::factory()->create();
        $evenement2 = Evenement::factory()->create();
        
        // Créer un utilisateur
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'role' => 'participant'
        ]);
        
        // Créer une inscription pour l'événement 1
        $inscription1 = Inscription::factory()->create(['id_user' => $user->id_user]);
        Inscription_event::create([
            'id_inscription' => $inscription1->id_inscription,
            'id_event' => $evenement1->id_event,
            'id_user' => $user->id_user,
        ]);
        
        // S'inscrire au même événement devrait fonctionner (car c'est un autre événement)
        $response = $this->post(route('inscription.store', $evenement2), [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'test@example.com',
            'telephone' => '0123456789',
            'password' => 'password123',
            'company' => 'Test Company',
        ]);
        
        // La validation doit réussir pour un autre événement
        $response->assertSessionHasNoErrors('email');
    }
}
