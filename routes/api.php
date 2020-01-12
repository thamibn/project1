<?php

/**
 *Authentication routes only, [public and protected ones]
 */
Route::group(['prefix' => 'auth'], function(){
    Route::post("/login", "AuthController@login");
    Route::post("/refresh/token", "AuthController@refreshToken");

    /**
     * Protected routes under authentication route group
     */
    Route::group(['middleware' => ['auth:api']], function (){
        Route::get("/user", "AuthController@authenticatedUser");
        Route::post("/logout", "AuthController@logout");
    });
});

/**
 * Application routes only
 */
Route::group(['middleware' => ['auth:api']],function (){
    Route::apiResource('users','UserController');
    Route::get('users/{user}/permissions', 'UserController@permissions');
    Route::put('users/{user}/permissions', 'UserController@updatePermissions');
    Route::apiResource('roles', 'RoleController');
    Route::get('roles/{role}/permissions', 'RoleController@permissions');
    Route::apiResource('permissions', 'PermissionController');
});

