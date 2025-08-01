<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatiereCoefClasseRequest extends FormRequest
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
            'matiere_id' => [
                'required',
                'integer',
                'exists:matieres,id'

            ],
            'classe_id' => 'required|integer|exists:classes,id',
            'coefficient' => 'required|integer|min:1',
            //
        ];
    }
}
