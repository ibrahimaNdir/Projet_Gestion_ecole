<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEleveRequest extends FormRequest
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
            'utilisateur_id'   => ['required', 'exists:users,id'],
            'nom'              => ['required', 'string'],
            'prenom'           => ['required', 'string'],
            'date_naissance'   => ['required', 'date'],
            'adresse'          => ['nullable', 'string'],
            'numero_matricule' => ['required', 'string', 'unique:eleves,numero_matricule']
        ];
    }

}
