<?php

namespace App\Services;

use App\Models\Eleve;
use Illuminate\Support\Str;

class EleveService
{
    public function create(array $data): Eleve
    {
        // Générer le numéro matricule automatiquement s’il n’est pas fourni
        if (empty($data['numero_matricule'])) {
            $count = Eleve::count() + 1;
            $data['numero_matricule'] = 'EL-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        }

        return Eleve::create($data);
    }

    public function update(Eleve $eleve, array $data): Eleve
    {
        $eleve->update($data);
        return $eleve;
    }
}
