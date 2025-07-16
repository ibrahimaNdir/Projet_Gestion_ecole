<?php

namespace App\services;
use App\Models\PeriodeEvaluation;

class PeriodeEvaluationService
{
    public function index()
    {
        $matieres = PeriodeEvaluation::all();
        return $matieres;
    }

    public function store( array $request)
    {
        $matiere =  PeriodeEvaluation::create($request);
        return $matiere;
    }

    public function destroy($id)
    {
        PeriodeEvaluation::destroy($id);
        return true;
    }

}
