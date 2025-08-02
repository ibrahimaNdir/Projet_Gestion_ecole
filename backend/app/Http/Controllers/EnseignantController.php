<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnseignantRequest;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
    protected $enseignantService;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->enseignantService = new EnseignantService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enseignants =  $this->enseignantService ->index();
        return response()->json($enseignants ,200);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $enseignant=  $this->enseignantService->store($request->validated());
        return response()->json($enseignant,201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $enseignant = Enseignant::find($id);
        return response()->json($enseignant,200);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EnseignantRequest $request, $id)
    {
        $validated = $request->validated();

        if($validated){
            $enseignant = Enseignant::find($id);
            $enseignant->update($validated);

        }
        return  response()->json($enseignant,200);
        //
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Enseignant::destroy($id);
        return response()->json("",204);
        //
    }

    public function countEnseignant()
    {
        try {
            $count = $this->enseignantService->count(); // Appel de la méthode count() du service
            return response()->json($count, 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du nombre de classes : ' . $e->getMessage()
            ], 500);
        }
    }
}
