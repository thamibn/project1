<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use App\Models\Role_Permission\Acl;
use App\Models\Role_Permission\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    /**
     * @var \App\Services\RoleService
     */
    private $roleService;

    /**
     * RoleController constructor.
     * @param \App\Services\RoleService $roleService
     */
    public function __construct(RoleService $roleService) {
        $this->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return $this->roleService->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role_Permission\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role_Permission\Role  $role
     * @return \App\Http\Resources\RoleResource|string
     */
    public function update(Request $request, Role $role)
    {
        return $this->roleService->update($request,$role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role_Permission\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }
}
