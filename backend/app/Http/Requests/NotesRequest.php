<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotesRequest extends FormRequest
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
            'eleve_id' => 'required|integer|exists:eleves,id', // S'assure que l'ID existe dans la table 'eleves'
            'cours_id' => 'required|integer|exists:cours,id',   // S'assure que l'ID existe dans la table 'cours'
            'valeur_note' => 'required|numeric|min:0|max:20', // Note sur 20, ou ajustez max selon votre barème
            'date_saisie' => 'required|date', // S'assure que c'est une date valide
            'periode_evaluation_id' => 'required|integer|exists:periodes_evaluation,id', // S'assure que l'ID existe dans la table 'periodes_evaluation'
            'type_evaluation' => 'nullable|string|max:255', // Ex: "Devoir Maison", "Examen"
            'commentaire_enseignant' => 'nullable|string', // C

            //
        ];
    }
}
