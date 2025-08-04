<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Classe::with(['eleves', 'enseignants'])->get();
        return response()->json($classes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'niveau' => 'required|string|max:50',
            'capacite' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $classe = Classe::create($request->all());
        return response()->json($classe, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classe $classe)
    {
        return response()->json($classe->load(['eleves', 'enseignants']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classe $classe)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'niveau' => 'sometimes|required|string|max:50',
            'capacite' => 'sometimes|required|integer|min:1',
            'description' => 'nullable|string',
            'statut' => 'sometimes|required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $classe->update($request->all());
        return response()->json($classe);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classe $classe)
    {
        $classe->delete();
        return response()->json(['message' => 'Classe supprimée avec succès']);
    }

    /**
     * Get the count of classes.
     */
    public function count()
    {
        $count = Classe::count();
        return response()->json(['count' => $count]);
    }
}
