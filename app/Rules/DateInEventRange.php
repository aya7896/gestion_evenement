<?php

namespace App\Rules;

use App\Models\Evenement;
use Illuminate\Contracts\Validation\Rule;

class DateInEventRange implements Rule
{
    private $eventId;
    private $evenement;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function passes($attribute, $value)
    {
        // Récupérer l'événement
        $this->evenement = Evenement::find($this->eventId);

        if (!$this->evenement) {
            return false;
        }

        // Vérifier que la date est dans l'intervalle
        $workshopDate = \Carbon\Carbon::parse($value);
        $eventStart = \Carbon\Carbon::parse($this->evenement->date_heure_debut)->startOfDay();
        $eventEnd = \Carbon\Carbon::parse($this->evenement->date_heure_fin)->startOfDay();

        return $workshopDate->between($eventStart, $eventEnd);
    }

    public function message()
    {
        if (!$this->evenement) {
            return 'L\'événement sélectionné est invalide.';
        }

        $start = \Carbon\Carbon::parse($this->evenement->date_heure_debut)->format('d/m/Y');
        $end = \Carbon\Carbon::parse($this->evenement->date_heure_fin)->format('d/m/Y');

        return "La date de l'atelier doit être comprise entre le $start et le $end (intervalle de l'événement).";
    }
}
