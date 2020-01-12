<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use App\Models\Role_Permission\Acl;
use App\Models\Role_Permission\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * @var \App\Services\PermissionService
     */
    private $permissionService;

    /**
     * PermissionController constructor.
     * @param \App\Services\PermissionService $permissionService
     */
    public function __construct(PermissionService $permissionService) {
        $this->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return $this->permissionService->all();
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
     * @param  \App\Models\Role_Permission\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role_Permission\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role_Permission\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
