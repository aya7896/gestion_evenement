<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvenementRequest extends FormRequest
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
            'type' => 'required|in:conference,conférence,workshop,seminaire,séminaire,formation,autre',
            'localisation' => 'nullable|string|max:255',
            'lieu' => 'nullable|string|max:255',
            'date_heure_debut' => 'required|date',
            'date_heure_fin' => 'required|date|after:date_heure_debut',
            'mode' => 'required|in:presentiel,présentiel,en ligne,hybride',
            'color_template' => 'nullable|in:violet,ocean,sunset,forest,slate',
            'hero_appearance' => 'nullable|in:glass_soft,glass_strong,clean,cinematic',
            'landing_template' => 'nullable|in:template_1,template_2,template_3,template_4',
            'landing_content' => 'nullable|array',
            'landing_content.hero_title' => 'nullable|string|max:255',
            'landing_content.hero_subtitle' => 'nullable|string|max:1000',
            'landing_content.primary_cta_text' => 'nullable|string|max:120',
            'landing_content.secondary_cta_text' => 'nullable|string|max:120',
            'id_entreprise' => 'nullable|exists:entreprises,id_entreprise',
            'visibility' => 'required|in:public,private',
            'status' => 'required|in:active,inactive',
            'validation_superAdmin' => 'nullable|boolean',
            'event_link' => 'nullable|url',
            'plaquette_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
