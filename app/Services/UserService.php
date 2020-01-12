<?php


namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use App\Models\Role_Permission\Permission;
use Illuminate\Auth\Access\AuthorizationException;

class UserService
{
    const ITEM_PER_PAGE = 10;

    /**
     * @param $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function all($request){
        $searchParams = $request->all();
        $userQuery = User::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $role = Arr::get($searchParams, 'role', '');
        $keyword = Arr::get($searchParams, 'keyword', '');

        if (!empty($role)) {
            $userQuery->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
        }

        if (!empty($keyword)) {
            $userQuery->where('name', 'LIKE', '%' . $keyword . '%');
            $userQuery->where('email', 'LIKE', '%' . $keyword . '%');
        }
        return UserResource::collection($userQuery->paginate($limit));
    }

    /**
     * @param $request
     * @return User
     * @throws Exception
     */
    public function create($request){
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();
        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }

        DB::commit();
        return $user;
    }

    /**
     * @param User $user
     * @return User
     */
    public function getById(User $user){
        return $user;
    }

    /**
     * @param      $request
     * @param User $user
     * @return \App\Models\User|string
     * @throws Exception
     */
    public function update($request, User $user){
        if ($user->isAdmin()) {
            return AuthorizationException::class;
        }
        DB::beginTransaction();
        try {
            $user->update($request->validated());
        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        return $user;
    }

    /**
     * @param User $user
     * @return null
     * @throws Exception
     */
    public function destroy(User $user){
        if ($user->isAdmin()) {
            return AuthorizationException::class;
        }
        try {
            $user->delete();
        } catch (Exception $e) {
            throw $e;
        }
        return NULL;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return \App\Http\Resources\UserResource|\Illuminate\Http\JsonResponse|string
     */
    public function updatePermissions(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return AuthorizationException::class;
        }

        $permissionIds = $request->get('permissions', []);
        $rolePermissionIds = $user->getPermissionsViaRoles()->pluck('id')->toArray();

        $newPermissionIds = array_diff($permissionIds, $rolePermissionIds);
        $permissions = Permission::allowed()->whereIn('id', $newPermissionIds)->get();
        $user->syncPermissions($permissions);
        return new UserResource($user);
    }

    /**
     * Get permissions from role
     *
     * @param User $user
     * @return \App\Models\User
     * @throws \Exception
     */
    public function permissions(User $user)
    {
        try {
//            return new JsonResponse([
//                'user' => PermissionResource::collection($user->getDirectPermissions()),
//                'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
//            ]);
            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
