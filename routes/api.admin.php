<?php

use Illuminate\Support\Facades\Route;

// auth
Route::middleware(['auth:api', 'can:admin'])->group(function () {
    Route::namespace('App\Http\Controllers')->group(function () {
        // Route::namespace('Admin')->controller('UserController')->group(function () {
        //     Route::get('/info_user', 'infoUser');
        // });
    });
});
