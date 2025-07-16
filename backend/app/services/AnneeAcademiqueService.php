<?php

namespace App\services;
use App\Models\AnneeAcademique;

class AnneeAcademiqueService
{
    public function index()
    {
        $matieres = AnneeAcademique::all();
        return $matieres;
    }

    public function store( array $request)
    {
        $matiere =  AnneeAcademique::create($request);
        return $matiere;
    }

    public function destroy($id)
    {
        AnneeAcademique::destroy($id);
        return true;
    }

}
