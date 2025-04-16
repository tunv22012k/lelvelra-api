<?php

use Illuminate\Support\Facades\Route;

// no auth
Route::namespace('App\Http\Controllers')->group(function () {
    Route::namespace('User')->controller('UserController')->group(function () {
        Route::post('/register', 'register');
        Route::get('/active_user_register/{userid}', 'activeUserRegister');
        Route::post('/reset_random_password', 'resetRandomPassword');
        Route::post('/forgot_password', 'forgotPassword');
        Route::post('/change_password', 'changePassword');
    });

    Route::controller('AuthController')->group(function () {
        Route::post('/login', 'login');
    });
});

// auth
// router có thế sử dụng cho toàn bộ role
Route::middleware(['auth:api'])->group(function () {
    Route::namespace('App\Http\Controllers')->group(function () {
        Route::namespace('User')->controller('UserController')->group(function () {
            Route::get('/info_user', 'infoUser');
        });

        Route::controller('AuthController')->group(function () {
            Route::post('/logout', 'logout');
            Route::post('/refresh_token', 'refreshToken');
        });
    });
});
