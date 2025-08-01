<?php

namespace App\services;
use App\Models\PeriodeEvaluation;

class PeriodeEvaluationService
{
    public function index()
    {
        return PeriodeEvaluation::with('anneeAcademique')->get();
    }

    public function store( array $request)
    {
        $periode=  PeriodeEvaluation::create($request);
        return $periode;
    }

    public function destroy($id)
    {
        PeriodeEvaluation::destroy($id);
        return true;
    }

}
