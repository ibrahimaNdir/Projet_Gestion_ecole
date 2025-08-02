<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeriodeEvaluationRequest extends FormRequest
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
            'nom_periode' => [
                'required',
                'string',
                'max:255',
            ],
            'annee_academique_id' => 'required|integer|exists:annees_academiques,id', // Doit être un ID valide d'une année académique existante
            'date_debut' => 'required|date',
            'date_fin'=>'required|date',

            //
        ];
    }
}
