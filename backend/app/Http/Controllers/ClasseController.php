<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClasseRequest;
use App\Models\Classe;
use App\services\ClasseService;
use Illuminate\Http\Request;


class ClasseController extends Controller
{
    protected $classeService;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->classeService = new ClasseService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classe =  $this->classeService ->index();
        return response()->json($classe,200);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $classe =  $this->classeService->store($request->validated());
        return response()->json($classe ,201);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $classe = Classe::find($id);
        return response()->json($classe,200);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClasseRequest $request, $id)
    {
        $validated = $request->validated();

        if($validated){
            $classe = Classe::find($id);
            $classe->update($validated);

        }
        return  response()->json($classe,200);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       Classe::destroy($id);
        return response()->json("",204);
        //
    }
}
