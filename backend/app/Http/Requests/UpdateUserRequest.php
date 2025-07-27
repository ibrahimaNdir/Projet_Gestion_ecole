<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id ?? null;

        return [
            'nom_utilisateur' => 'sometimes|required|string|max:255|unique:users,nom_utilisateur,' . $userId,
            'email'           => 'sometimes|required|email|unique:users,email,' . $userId,
            'mot_de_passe'    => 'nullable|string|min:6|confirmed',
            'role_id'         => 'sometimes|required|exists:roles,id',
        ];
    }
}
