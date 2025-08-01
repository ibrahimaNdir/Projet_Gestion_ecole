<?php

namespace App\services;
use App\Models\Classe;

class ClasseService
{
    public function index()
    {
        $classes= Classe::all();
        return $classes;
    }

    public function store( array $request)
    {
        $classe =  Classe::create($request);
        return $classe;
    }

    public function destroy($id)
    {
        Classe::destroy($id);
        return true;
    }
    public function count(): int
    {
        return Classe::count();
    }

}
