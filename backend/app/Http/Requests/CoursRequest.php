<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoursRequest extends FormRequest
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
            'enseignant_id' => [
                'required',
                'integer',
                'exists:enseignants,id', // S'assure que l'ID existe dans la table 'enseignants'
                // Règle d'unicité combinée : assure que la combinaison (enseignant_id, matiere_id, classe_id, annee_academique_id) est unique.
                // Cela évite d'avoir deux fois la même instance de cours.
                Rule::unique('cours')->where(function ($query) {
                    return $query->where('matiere_id', $this->input('matiere_id'))
                        ->where('classe_id', $this->input('classe_id'))
                        ->where('annee_academique_id', $this->input('annee_academique_id'));
                }),
            ],
            'matiere_id' => 'required|integer|exists:matieres,id', // S'assure que l'ID existe dans la table 'matieres'
            'classe_id' => 'required|integer|exists:classes,id',   // S'assure que l'ID existe dans la table 'classes'

            //
        ];
    }
}
