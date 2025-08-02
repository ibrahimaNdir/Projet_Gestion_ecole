<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnseignantMatiereRequest extends FormRequest
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
                // Règle d'unicité combinée : assure que la paire (enseignant_id, matiere_id, annee_academique_id) est unique.
                // Cela évite d'avoir deux fois le même enseignant affecté à la même matière pour la même année.
                Rule::unique('enseignant_matiere')->where(function ($query) {
                    return $query->where('matiere_id', $this->input('matiere_id'))
                        ->where('annee_academique_id', $this->input('annee_academique_id'));
                }),
            ],
            'matiere_id' => 'required|integer|exists:matieres,id', // S'assure que l'ID existe dans la table 'matieres'
            'annee_academique_id' => 'required|integer|exists:annees_academiques,id', // S'assure que l'ID existe dans la table 'annees_academiques'
            // Ajoutez ici d'autres règles si votre table 'enseignant_matiere' a des colonnes supplémentaires.
        ];
    }
}
