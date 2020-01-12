<?php


namespace App\Services;


use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;
use App\Models\Role_Permission\Role;
use App\Http\Resources\PermissionResource;
use App\Models\Role_Permission\Permission;
use Illuminate\Auth\Access\AuthorizationException;

class RoleService
{
    public function all(){
        return RoleResource::collection(Role::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role $role
     * @return \App\Http\Resources\RoleResource|string
     */
    public function update(Request $request, Role $role)
    {
        if ($role === null || $role->isAdmin()) {
            return AuthorizationException::class;
        }

        $permissionIds = $request->get('permissions', []);
        $permissions = Permission::allowed()->whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissions);
        $role->save();
        return new RoleResource($role);
    }

    /**
     * Get permissions from role
     *
     * @param Role $role
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function permissions(Role $role)
    {
        return PermissionResource::collection($role->permissions);
    }
}
