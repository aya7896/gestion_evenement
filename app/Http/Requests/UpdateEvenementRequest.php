<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvenementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'capacite' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'type' => 'required|in:conférence,workshop,séminaire,formation,autre',
            'localisation' => 'nullable|string|max:255',
            'lieu' => 'nullable|string|max:255',
            'date_heure_debut' => 'required|date',
            'date_heure_fin' => 'required|date|after:date_heure_debut',
            'mode' => 'required|in:présentiel,en ligne,hybride',
            'visibility' => 'required|in:public,private',
            'status' => 'required|in:active,inactive',
            'validation_superAdmin' => 'required|boolean',
            'event_link' => 'nullable|url',
            'plaquette_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
