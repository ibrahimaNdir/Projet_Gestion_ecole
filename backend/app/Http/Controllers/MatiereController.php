<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatiereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matieres = Matiere::with(['classes'])->get();
        return response()->json($matieres);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:matieres,code',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $matiere = Matiere::create($request->all());
        return response()->json($matiere, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Matiere $matiere)
    {
        return response()->json($matiere->load(['classes']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Matiere $matiere)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:50|unique:matieres,code,' . $matiere->id,
            'description' => 'nullable|string',
            'statut' => 'sometimes|required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $matiere->update($request->all());
        return response()->json($matiere);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Matiere $matiere)
    {
        $matiere->delete();
        return response()->json(['message' => 'Matière supprimée avec succès']);
    }

    /**
     * Get the count of subjects.
     */
    public function count()
    {
        $count = Matiere::count();
        return response()->json(['count' => $count]);
    }
}
