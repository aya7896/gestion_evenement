<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAtelierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Backwards compatibility: accept either 'id_evenement' or 'id_event'
        if ($this->has('id_evenement') && !$this->has('id_event')) {
            $this->merge(['id_event' => $this->input('id_evenement')]);
        }
    }

    public function rules(): array
    {
        return [
            'id_event' => 'required|exists:evenements,id_event',
            'titre' => 'required|string|max:255',
            'date' => ['required', 'date', new \App\Rules\DateInEventRange($this->input('id_event'))],
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'capacite' => 'required|integer|min:1',
            'banniere' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'id_event.required' => "Le champ événement est requis.",
            'id_event.exists' => "L'événement sélectionné est invalide.",
            'titre.required' => "Le titre est requis.",
            'date.required' => "La date est requise.",
        ];
    }
}