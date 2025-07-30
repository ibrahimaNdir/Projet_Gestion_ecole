<?php

namespace App\services;

use App\Models\Enseignant;

class EnseignantService
{
    public function index()
    {
        $enseignants = Enseignant::all();
        return $enseignants ;
    }

    public function store( array $request)
    {
        $enseignant =  Enseignant ::create($request);
        return $enseignant ;
    }

    public function destroy($id)
    {
        Enseignant ::destroy($id);
        return true;
    }
    /**
     * Compte le nombre total d'enseignants.
     *
     * @return int Le nombre total d'enseignants.
     */
    public function count(): int
    {
        return Enseignant::count();
    }

}
