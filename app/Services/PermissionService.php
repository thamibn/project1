<?php


namespace App\Services;


use App\Http\Resources\PermissionResource;
use App\Models\Role_Permission\Permission;

class PermissionService
{
    public function all(){
        return PermissionResource::collection(Permission::all());
    }
}
