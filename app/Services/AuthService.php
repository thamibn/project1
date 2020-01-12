<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Contracts\Container\BindingResolutionException;

class AuthService
{
    use ThrottlesLogins;

    const REFRESH_TOKEN = 'refreshToken'; // change to the max attempts you want.
    public $maxAttempts = 4; // change to the minutes you want.
    public $decayMinutes = 1;

    /**
     * @param $request
     * @return array|void
     * @throws ValidationException
     * @throws BindingResolutionException
     */
    public function attemptLogin($request)
    {
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        if (!Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $this->incrementLoginAttempts($request);
            throw $this->validationFailed();
        }
        return $this->proxy('password', [
            'username' => request()->email,
            'password' => request()->password
        ]);
    }

    /**
     * @return array
     * @throws ValidationException
     * @throws BindingResolutionException
     */
    public function attemptRefresh(): array
    {
        try {
            $refreshToken = request()->cookie(self::REFRESH_TOKEN);
            return $this->proxy('refresh_token', [
                'refresh_token' => $refreshToken
            ]);
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @return null
     */
    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);
        $accessToken->revoke();
        cookie()->queue(cookie()->forget(self::REFRESH_TOKEN));
        return NULL;
    }

    /**
     * @param $grantType
     * @param array $data
     * @return array
     * @throws ValidationException
     * @throws BindingResolutionException
     */
    private function proxy($grantType, array $data = [])
    {
        $postData = array_merge($data, [
            'grant_type' => $grantType,
            'client_id' => config('passportClient.CLIENT_ID'),
            'client_secret' => config('passportClient.CLIENT_SECRET'),
            'scope' => '',
        ]);
        $results = $this->makeRequest($postData);

        return [
            'user' => new UserResource(auth()->user()),
            'access_token' => $results->access_token,
            'toke_type' => 'Bearer',
            'expires_in' => Carbon::parse(now()->addSeconds($results->expires_in))->toDateTimeString(),
            'refresh_token' => $results->refresh_token,
        ];
    }

    private function makeRequest(array $postData = []){
        $request = app()->make('request');
        $request->request->add($postData);
        $tokenRequest = Request::create(
            env('APP_URL') . '/oauth/token',
            'post'
        );

        $response = Route::dispatch($tokenRequest);
        if ($response->getStatusCode() === 200) {
            $results = json_decode($response->getContent());
        } else {
            throw $this->validationFailed();
        }

        $this->clearLoginAttempts($request);
        return $results;
    }

    /**
     * @return ValidationException
     */
    private function validationFailed(): ValidationException
    {
        return ValidationException::withMessages([
            'email' => ['Login failed email or password incorrect']
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username(): string
    {
        return 'email';
    }

    public function authUser(){
        return auth()->user();
    }

}
