<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @return string
     * @throws AuthenticationException
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            throw new AuthenticationException();
        }
    }
}
