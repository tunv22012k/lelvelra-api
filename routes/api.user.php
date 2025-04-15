<?php

use Illuminate\Support\Facades\Route;

// auth
Route::middleware(['auth:api', 'can:user'])->group(function () {
    Route::namespace('App\Http\Controllers')->group(function () {
        Route::namespace('User')->controller('UserController')->group(function () {
            Route::get('/info', 'infoUser');
        });
    });
});
