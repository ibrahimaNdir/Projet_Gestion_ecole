<?php

namespace App\services;

use App\Models\Role;

class RoleService
{
    public function index()
    {
        $role = Role::all();
        return $role;
    }

    public function store( array $request)
    {
        $role =  Role::create($request);
        return $role;
    }

    public function destroy($id)
    {
        Role::destroy($id);
        return true;
    }


}
