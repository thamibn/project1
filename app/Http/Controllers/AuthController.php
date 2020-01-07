<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Container\BindingResolutionException;

class AuthController extends Controller
{
    const REFRESH_TOKEN = 'refreshToken';
    private $authService;

    /**
     * AuthController constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    /**
     * @param LoginDetails $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function login(LoginDetails $request){
        try {
            $data = $this->authService->attemptLogin($request);
            $refreshToken = $data['refresh_token'];
            unset($data['refresh_token']);
            return response()->json([
                'success' => true,
                'message' => "Successfully logged in",
                'data' => $data,
                ])->withCookie(self::REFRESH_TOKEN,$refreshToken, 14400); //valid for 10 days since the refresh token is also valid for 10 days
        } catch (ValidationException $e) {
            throw $e;
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function refreshToken(Request $request)
    {
        try {
            $data = $this->authService->attemptRefresh();
            $refreshToken = $data['refresh_token'];
            unset($data['refresh_token']);
            return response()->json([
                'success' => true,
                'message' => "Successfully refreshed token",
                'data' => $data,
            ])->withCookie(self::REFRESH_TOKEN,$refreshToken, 14400);
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request){
        $data = $this->authService->logout($request);
        return $this->successResponse("logout successfully", $data);
    }

    /**
     * @return JsonResponse
     */
    public function user(){
        return $this->successResponse("user", Auth::user());
    }

}
