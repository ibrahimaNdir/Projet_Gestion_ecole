<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEleveRequest;
use App\Http\Requests\UpdateEleveRequest;
use Illuminate\Http\Request;
use App\Models\Eleve;
use App\Services\EleveService;


    /**
     * Display a listing of the resource.
     */
class EleveController extends Controller
{
    protected $eleveService;
    public function index()
    {
        return Eleve::with('user')->paginate(10);
    }


    public function __construct(EleveService $eleveService)
    {
        $this->eleveService = $eleveService;
    }

    public function store(StoreEleveRequest $request)
    {
        $eleve = $this->eleveService->create($request->validated());
        return response()->json($eleve, 201);
    }

    public function update(UpdateEleveRequest $request, Eleve $eleve)
    {
        $eleve = $this->eleveService->update($eleve, $request->validated());
        return response()->json($eleve);
    }

}
