<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreParentRequest;
use App\Http\Requests\UpdateParentRequest;
use App\Models\Eleve;
use App\Models\ParentUser;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ParentUser::with('user', 'enfants')->paginate(10);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParentRequest $request)
    {
        $parent = ParentUser::create($request->validated());
        return response()->json($parent, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ParentUser $parent)
    {
        return $parent->load('user', 'enfants');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParentRequest $request, ParentUser $parent)
    {
        $parent->update($request->validated());
        return response()->json($parent);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParentUser $parent)
    {
        $parent->delete();
        return response()->noContent();
    }
    // Attacher un élève à un parent
    public function attachEleve(Request $request, ParentUser $parent, Eleve $eleve)
    {
        $parent->enfants()->syncWithoutDetaching([
            $eleve->id => ['type_relation' => $request->input('type_relation')]
        ]);

        return response()->json(['message' => 'Élève lié au parent avec succès']);
    }
}
