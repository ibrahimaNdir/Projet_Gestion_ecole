<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulletinsRequest extends FormRequest
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
            'eleve_id' => [
                'required',
                'integer',
                'exists:eleves,id', // S'assure que l'ID existe dans la table 'eleves'
                // Règle d'unicité composite : un élève ne peut avoir qu'un seul bulletin
                // pour une période donnée dans une année académique donnée.
                Rule::unique('bulletins')->where(function ($query) {
                    return $query->where('annee_academique_id', $this->input('annee_academique_id'))
                        ->where('periode_evaluation_id', $this->input('periode_evaluation_id'));
                })->ignore($bulletinId, 'id'), // Ignorer l'enregistrement actuel lors de la mise à jour
            ],
            'annee_academique_id' => 'required|integer|exists:annees_academiques,id', // S'assure que l'ID existe dans la table 'annees_academiques'
            'periode_evaluation_id' => 'required|integer|exists:periodes_evaluation,id', // S'assure que l'ID existe dans la table 'periodes_evaluation'
            'date_generation' => 'required|date', // S'assure que c'est une date valide
            'url_bulletin_pdf' => 'nullable|string|max:255', // Chemin d'accès au fichier PDF
            'moyenne_generale' => 'nullable|numeric|min:0|max:20', // Note sur 20, ou ajustez max selon votre barème
            'mention' => 'nullable|string|max:255', // Ex: "Assez Bien"
            'rang_classe' => 'nullable|integer|min:1', // Le rang doit être un entier positif
            'appreciation_generale' => 'nullable|string', // Champ texte pour l'appréciation
            //
        ];
    }
}
