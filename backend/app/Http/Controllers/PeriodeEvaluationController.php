<?php

namespace App\Http\Controllers;

use App\Http\Requests\PeriodeEvaluationRequest;
use App\Models\PeriodeEvaluation;
use App\services\PeriodeEvaluationService;
use Illuminate\Http\Request;

class PeriodeEvaluationController extends Controller
{
    protected $periodeevaluationService;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->periodeevaluationService = new PeriodeEvaluationService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periode=  $this->periodeevaluationService ->index();
        return response()->json($periode,200);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $periode =  $this->periodeevaluationService->store($request->validated());
        return response()->json($periode,201);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $periode = PeriodeEvaluation::find($id);
        return response()->json($periode,200);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PeriodeEvaluationRequest $request, $id)
    {
        $validated = $request->validated();

        if($validated){
            $periode = PeriodeEvaluation::find($id);
            $periode->update($validated);

        }
        return  response()->json($periode,200);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        PeriodeEvaluation::destroy($id);
        return response()->json("",204);
        //
    }
}
