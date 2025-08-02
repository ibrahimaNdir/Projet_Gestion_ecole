<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnseignantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'utilisateur_id' => [
                'required',
                'integer',
                'exists:users,id', // S'assure que l'ID existe dans la table 'users'
                // S'assure que l'utilisateur_id est unique dans la table 'enseignants'.
                // Pour une opération de création, cela vérifie simplement l'unicité.
                // Pour une opération de mise à jour, il faudrait ajouter ->ignore($this->enseignant->id) si l'enseignant est déjà chargé.
                Rule::unique('enseignants', 'utilisateur_id'),
            ],
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date', // 'date' valide un format de date
            'telephone' => 'nullable|string|max:20', // Longueur raisonnable pour un numéro de téléphone
            'adresse' => 'nullable|string',
            //
        ];
    }
}
