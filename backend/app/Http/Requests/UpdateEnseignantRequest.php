<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnseignantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('enseignant')->id ?? null;

        return [
            'nom'        => 'sometimes|required|string|max:255',
            'prenom'     => 'sometimes|required|string|max:255',
            'telephone'  => 'nullable|string|max:20',
            'specialite' => 'nullable|string|max:255',
        ];
    }
}
