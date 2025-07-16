<?php

namespace App\services;

use App\Models\Enseignant;

class EnseignantService
{
    public function index()
    {
        $enseignant = Enseignant::all();
        return $enseignant;
    }

    public function store( array $request)
    {
        $enseignant = Enseignant::create($request);
        return $enseignant;
    }

    public function destroy($id)
    {
        Enseignant::destroy($id);
        return true;
    }

}
