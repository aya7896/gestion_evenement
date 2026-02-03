<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollaborateurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'id_entreprise' => 'required|exists:entreprises,id_entreprise',
            'role' => 'required|in:admin_entreprise,scanner',
            'active' => 'required|boolean',
        ];
    }
}
