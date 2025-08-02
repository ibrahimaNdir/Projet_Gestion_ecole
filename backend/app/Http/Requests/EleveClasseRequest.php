<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EleveClasseRequest extends FormRequest
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
                // Règle d'unicité combinée : assure que la paire (eleve_id, classe_id, annee_academique_id) est unique.
                // Cela évite d'avoir deux fois le même élève affecté à la même classe pour la même année.
                Rule::unique('eleve_classe')->where(function ($query) {
                    return $query->where('classe_id', $this->input('classe_id'))
                        ->where('annee_academique_id', $this->input('annee_academique_id'));
                })->ignore($eleveClasseId, 'id'), // Ignorer l'enregistrement actuel lors de la mise à jour
            ],
            'classe_id' => 'required|integer|exists:classes,id', // S'assure que l'ID existe dans la table 'classes'
            'annee_academique_id' => 'required|integer|exists:annees_academiques,id', // S'assure que l'ID existe dans la table 'annees_academiques'

            //
        ];
    }
}
