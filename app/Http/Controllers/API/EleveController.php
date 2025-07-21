<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEleveRequest;
use App\Http\Requests\UpdateEleveRequest;
use Illuminate\Http\Request;
use App\Models\Eleve;

class EleveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

        {
            return Eleve::with('user')->paginate(10);
        }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEleveRequest $request)
    {
        $eleve = Eleve::create($request->validated());
        return response()->json($eleve, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $eleve)
    {
        return $eleve->load('user', 'parents');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEleveRequest $request, Eleve $eleve)
    {
        $eleve->update($request->validated());
        return response()->json($eleve);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $eleve)
    {
        $eleve->delete();
        return response()->noContent();
    }
}
