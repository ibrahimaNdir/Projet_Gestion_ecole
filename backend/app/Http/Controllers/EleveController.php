<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EleveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eleves = Eleve::with(['parents', 'classes'])->get();
        return response()->json($eleves);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'adresse' => 'required|string',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|unique:eleves,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $eleve = Eleve::create($request->all());
        return response()->json($eleve, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Eleve $eleve)
    {
        return response()->json($eleve->load(['parents', 'classes']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Eleve $eleve)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'date_naissance' => 'sometimes|required|date',
            'lieu_naissance' => 'sometimes|required|string|max:255',
            'sexe' => 'sometimes|required|in:M,F',
            'adresse' => 'sometimes|required|string',
            'telephone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|unique:eleves,email,' . $eleve->id,
            'statut' => 'sometimes|required|in:actif,inactif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $eleve->update($request->all());
        return response()->json($eleve);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Eleve $eleve)
    {
        $eleve->delete();
        return response()->json(['message' => 'Élève supprimé avec succès']);
    }

    /**
     * Get the count of students.
     */
    public function count()
    {
        $count = Eleve::count();
        return response()->json(['count' => $count]);
    }
}