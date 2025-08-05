<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\services\RoleService;

class RoleController
{
    protected $roleService;
    /**
     * Display a listing of the resource.
     */
    public function __construct(    RoleService $roleService)
    {
        $this->$roleService = $roleService;
    }
    public function index()
    {
        $roles =  $this->roleService ->index();
        return response()->json($roles,200);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {

        $role =  $this->roleService->store($request->validated());
        return response()->json($role,201);

        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $role = Role::find($id);
        return response()->json( $role,200);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, $id)
    {
        $validated = $request->validated();

        if($validated){
            $role = Role::find($id);
            $role->update($validated);

        }
        return  response()->json( $role,200);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       Role::destroy($id);
        return response()->json("",204);
        //
    }


}
