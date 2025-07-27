<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom_utilisateur' => 'required|string|max:255|unique:users,nom_utilisateur',
            'email'           => 'required|email|unique:users,email',
            'mot_de_passe'    => 'required|string|min:6|confirmed',
            'role_id'         => 'required|exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Cet email est déjà utilisé.',
            'mot_de_passe.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'role_id.exists' => 'Le rôle sélectionné est invalide.',
        ];
    }
}
