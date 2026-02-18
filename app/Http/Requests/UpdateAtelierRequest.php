<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAtelierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre'       => 'required|string|max:255',
            'date'        => 'required|date',
            'heure_debut' => 'nullable|date_format:H:i',
            'heure_fin'   => 'nullable|date_format:H:i|sometimes|required_with:heure_debut|after_or_equal:heure_debut',
            'visibility'  => 'nullable|in:public,private',
            'sujet'       => 'nullable|string',
            'description' => 'nullable|string',
            'capacite'    => 'nullable|integer|min:1',
            'status'      => 'nullable|in:active,inactive',
            'online_link' => 'nullable|url',
            'banniere'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'speakers'    => 'nullable|array',
            'speakers.*'  => 'integer|exists:speakers,id_speaker',
        ];
    }
}