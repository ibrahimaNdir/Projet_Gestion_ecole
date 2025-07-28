<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Http\Requests\StoreEnseignantRequest;
use App\Http\Requests\UpdateEnseignantRequest;
use App\Models\Enseignant;
use App\Services\EnseignantService;

class EnseignantController extends Controller
{
    protected $enseignantService;

    public function __construct(EnseignantService $enseignantService)
    {
        $this->enseignantService = $enseignantService;
    }

    public function index()
    {
        return response()->json(Enseignant::with('user')->get());
    }

    public function store(StoreEnseignantRequest $request)
    {
        $enseignant = $this->enseignantService->create($request->validated());
        return response()->json($enseignant, 201);
    }

    public function show(Enseignant $enseignant)
    {
        return response()->json($enseignant->load('user'));
    }

    public function update(UpdateEnseignantRequest $request, Enseignant $enseignant)
    {
        $enseignant = $this->enseignantService->update($enseignant, $request->validated());
        return response()->json($enseignant);
    }

    public function destroy(Enseignant $enseignant)
    {
        $enseignant->delete();
        return response()->json(['message' => 'Enseignant supprimé']);
    }
}

