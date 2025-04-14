<?php

use Illuminate\Support\Facades\Route;

// no auth
Route::namespace('App\Http\Controllers')->group(function () {
    Route::namespace('User')->controller('UserController')->group(function () {
        Route::post('/register', 'register');
    });

    Route::controller('AuthController')->group(function () {
        Route::post('/login', 'login');
    });
});

// auth
Route::middleware(['auth:api', 'can:user'])->group(function () {
    Route::namespace('App\Http\Controllers')->group(function () {
        Route::namespace('User')->prefix('user')->controller('UserController')->group(function () {
            Route::get('/info_user', 'infoUser');
        });

        Route::controller('AuthController')->group(function () {
            Route::post('/logout', 'logout');
        });
    });
});
