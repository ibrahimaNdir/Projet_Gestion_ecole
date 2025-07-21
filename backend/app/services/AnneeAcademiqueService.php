<?php

namespace App\services;
use App\Models\AnneeAcademique;

class AnneeAcademiqueService
{
    public function index()
    {
        $annees = AnneeAcademique::all();
        return $annees;
    }

    public function store( array $request)
    {
        $annee=  AnneeAcademique::create($request);
        return $annee;
    }

    public function destroy($id)
    {
        AnneeAcademique::destroy($id);
        return true;
    }

}
