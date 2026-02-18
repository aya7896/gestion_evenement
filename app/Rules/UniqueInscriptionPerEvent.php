<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;
use App\Models\Inscription_event;

class UniqueInscriptionPerEvent implements ValidationRule
{
    private $eventId;
    private $errorMessage = '';

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Normaliser l'email
        $email = strtolower(trim((string) $value));
        
        // Vérifier si un utilisateur avec cet email existe déjà
        $user = User::where('email', $email)->first();

        if ($user) {
            // Vérifier si cet utilisateur a déjà une inscription pour cet événement
            // en utilisant la nouvelle colonne id_user dans inscription_event
            $existingInscription = Inscription_event::where('id_user', $user->id_user)
                ->where('id_event', $this->eventId)
                ->exists();

            if ($existingInscription) {
                $this->errorMessage = "Cet email est déjà inscrit à cet événement.";
                $fail($this->errorMessage);
            }
        }
    }
}
