<?php

namespace App\services;
use App\Models\Matiere;


class MatiereService
{
    public function index()
    {
        $matieres = Matiere::all();
        return $matieres;
    }

    public function store( array $request)
    {
        $matiere =  Matiere::create($request);
        return $matiere;
    }

    public function destroy($id)
    {
        Matiere::destroy($id);
        return true;
    }
    public function count(): int
    {
        return Matiere::count();
    }


}
