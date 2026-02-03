<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEntrepriseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'logo' => 'nullable|file|image|max:2048',
            'site_web' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'tel' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'secteur_activite' => 'nullable|string|max:255',
            'taille_entreprise' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
        ];
    }
}
