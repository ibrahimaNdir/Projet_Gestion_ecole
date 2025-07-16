<?php

namespace App\services;
use App\Models\Classe;

class ClasseService
{
    public function index()
    {
        $matieres = Classe::all();
        return $matieres;
    }

    public function store( array $request)
    {
        $matiere =  Classe::create($request);
        return $matiere;
    }

    public function destroy($id)
    {
        Classe::destroy($id);
        return true;
    }

}
