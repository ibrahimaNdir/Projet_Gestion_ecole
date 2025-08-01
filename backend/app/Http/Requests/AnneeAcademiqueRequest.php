<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnneeAcademiqueRequest extends FormRequest
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
            'annee_debut' => [
                'required',
                'digits:4', // Doit être 4 chiffres (ex: 2024)
                'integer',

            ],
            'annee_fin' => [
                'required',
                'digits:4',
                'integer',
                'after:annee_debut',

            ],
            'est_actuelle' => 'boolean', // Doit être true ou false

            //
        ];
    }
}
