<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreParentRequest;
use App\Http\Requests\UpdateParentRequest;
use App\Models\Eleve;
use App\Models\ParentUser;
use Illuminate\Http\Request;

  /**
     * Display a listing of the resource.
     */
    use App\Services\ParentService;

class ParentController extends Controller
{
    public function index()
    {
        return response()->json(ParentUser::with('eleves')->paginate(10));
    }

    protected $parentService;

    public function __construct(ParentService $parentService)
    {
        $this->parentService = $parentService;
    }

    public function store(StoreParentRequest $request)
    {
        $parent = $this->parentService->create($request->validated());
        return response()->json($parent, 201);
    }

    public function update(UpdateParentRequest $request, ParentUser $parent)
    {
        $parent = $this->parentService->update($parent, $request->validated());
        return response()->json($parent);
    }

    public function attachEleve(ParentUser $parent, $eleveId)
    {
        $this->parentService->attachEleve($parent, $eleveId);
        return response()->json(['message' => 'Élève attaché avec succès']);
    }

}
