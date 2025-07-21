<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatiereRequest;
use App\Models\Matiere;

use App\services\MatiereService;

class MatiereController extends Controller
{
    protected $matiereService;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->matiereService = new MatiereService();
    }
    public function index()
    {
        $matieres =  $this->matiereService ->index();
        return response()->json($matieres,200);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MatiereRequest $request)
    {

        $matiere =  $this->matiereService->store($request->validated());
        return response()->json($matiere,201);

        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $matiere = Matiere::find($id);
        return response()->json($matiere,200);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MatiereRequest $request, $id)
    {
        $validated = $request->validated();

        if($validated){
            $matiere = Matiere::find($id);
            $matiere->update($validated);

        }
        return  response()->json($matiere,200);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Matiere::destroy($id);
        return response()->json("",204);
        //
    }
}
