<?php

namespace App\Http\Controllers;

use App\Models\AnneeAcademique;
use App\services\AnneeAcademiqueService;
use Illuminate\Http\Request;
use App\Http\Requests\AnneeAcademiqueRequest;




class AnneeAcademiqueController extends Controller
{
    protected  $anneeacademiqueService;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->anneeacademiqueService = new AnneeAcademiqueService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anneeacademique =  $this->anneeacademiqueService ->index();
        return response()->json($anneeacademique ,200);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $anneeacademique =  $this->anneeacademiqueService->store($request->validated());
        return response()->json($anneeacademique ,201);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $anneeacademique = AnneeAcademique::find($id);
        return response()->json($anneeacademique,200);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AnneeAcademiqueRequest $request, $id)
    {
        $validated = $request->validated();

        if($validated){
            $anneeacademique = AnneeAcademique::find($id);
            $anneeacademique->update($validated);

        }
        return  response()->json($anneeacademique,200);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        AnneeAcademique::destroy($id);
        return response()->json("",204);
        //
    }
}
