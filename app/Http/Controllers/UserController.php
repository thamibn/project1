<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateUser;
use App\Http\Requests\UpdateUser;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {

        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return $this->userService->all($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUser $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(CreateUser $request)
    {
        $user = $this->userService->create($request);
        return $this->successResponse("User was created successfully", new UserResource($user));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        $user = $this->userService->getById($user);
        return $this->successResponse("User", new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUser $request
     * @param User       $user
     * @return JsonResponse
     * @throws Exception
     */
    public function update(UpdateUser $request, User $user)
    {
        $user = $this->userService->update($request, $user);
        return $this->successResponse("User was updated successfully", new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(User $user)
    {
        $this->userService->destroy($user);
        return $this->successResponse("User was deleted successfully", "");
    }
}
