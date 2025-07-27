<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEleveRequest extends FormRequest
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
            'nom'              => ['sometimes', 'string'],
            'prenom'           => ['sometimes', 'string'],
            'date_naissance'   => ['sometimes', 'date'],
            'adresse'          => ['nullable', 'string'],
            'numero_matricule' => ['sometimes', 'string', Rule::unique('eleves')->ignore($this->eleve)]
        ];
    }


}
