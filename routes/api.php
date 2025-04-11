<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Route::middleware(['auth:api'])->group(function () {
//     Route::namespace('App\Http\Controllers')->group(function () {
//         Route::namespace('Folder')->prefix('user')->controller('AuthController')->group(function () {
//             Route::get('/me', [AuthController::class, 'me']);
//             Route::post('/logout', [AuthController::class, 'logout']);
//         });
//     });
// });

Route::prefix('admin')->group(function () {
//     Route::post('login', [AdminAuthController::class, 'login']);

//     Route::middleware('auth:admin-api')->group(function () {
//         Route::get('me', [AdminAuthController::class, 'me']);
//         Route::post('logout', [AdminAuthController::class, 'logout']);
//     });
});
