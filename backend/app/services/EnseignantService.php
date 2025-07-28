<?php

namespace App\services;

use App\Models\Enseignant;

class EnseignantService
{
    public function create(array $data): Enseignant
    {
        return Enseignant::create($data);
    }

    public function update(Enseignant $enseignant, array $data): Enseignant
    {
        $enseignant->update($data);
        return $enseignant;
    }
}
