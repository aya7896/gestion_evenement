<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntrepriseRequest extends FormRequest
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
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'code_postal' => 'required|string|max:20',
            'pays' => 'required|string|max:100',
            'secteur_activite' => 'required|string|max:255',
            'type_entreprise' => 'required|string|max:100',
            'effectif' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de l\'entreprise est obligatoire.',
            'nom.string' => 'Le nom doit être un texte valide.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            
            'secteur_activite.required' => 'Le secteur d\'activité est obligatoire.',
            'secteur_activite.string' => 'Le secteur d\'activité doit être un texte valide.',
            'secteur_activite.max' => 'Le secteur d\'activité ne peut pas dépasser 255 caractères.',
            
            'effectif.required' => 'L\'effectif est obligatoire.',
            'effectif.integer' => 'L\'effectif doit être un nombre entier.',
            'effectif.min' => 'L\'effectif doit être au moins 1.',
            
            'taille_entreprise.required' => 'La taille de l\'entreprise est obligatoire.',
            'taille_entreprise.in' => 'La taille sélectionnée n\'est pas valide.',
            
            'type_entreprise.required' => 'Le type d\'entreprise est obligatoire.',
            'type_entreprise.string' => 'Le type d\'entreprise doit être un texte valide.',
            'type_entreprise.max' => 'Le type d\'entreprise ne peut pas dépasser 100 caractères.',
            
            'adresse.required' => 'L\'adresse est obligatoire.',
            'adresse.string' => 'L\'adresse doit être un texte valide.',
            'adresse.max' => 'L\'adresse ne peut pas dépasser 255 caractères.',
            
            'ville.required' => 'La ville est obligatoire.',
            'ville.string' => 'La ville doit être un texte valide.',
            'ville.max' => 'La ville ne peut pas dépasser 100 caractères.',
            
            'code_postal.required' => 'Le code postal est obligatoire.',
            'code_postal.string' => 'Le code postal doit être un texte valide.',
            'code_postal.max' => 'Le code postal ne peut pas dépasser 20 caractères.',
            
            'pays.required' => 'Le pays est obligatoire.',
            'pays.string' => 'Le pays doit être un texte valide.',
            'pays.max' => 'Le pays ne peut pas dépasser 100 caractères.',
            
            'email.email' => 'L\'email doit être valide.',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères.',
            
            'tel.string' => 'Le téléphone doit être un texte valide.',
            'tel.max' => 'Le téléphone ne peut pas dépasser 20 caractères.',
            
            'site_web.url' => 'Le site web doit être une URL valide.',
            'site_web.max' => 'Le site web ne peut pas dépasser 255 caractères.',
            
            'logo.file' => 'Le logo doit être un fichier valide.',
            'logo.image' => 'Le logo doit être une image.',
            'logo.max' => 'Le logo ne peut pas dépasser 2 MB.',
            
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }
}
