<?php

use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')->prefix('api/user')->group(base_path('routes/api.user.php'));
            Route::middleware('api')->prefix('api/salesman')->group(base_path('routes/api.salesman.php'));
            Route::middleware('api')->prefix('api/admin')->group(base_path('routes/api.admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
        ]);
        $middleware->group('guest', [
            \App\Http\Middleware\RedirectIfAuthenticated::class,
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
        $middleware->group('api', [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // validate -> 422
        $exceptions->renderable(function (ValidationException $e, $request) {
            return ApiResponse::validation($e->errors());
        });

        // 401 - chưa login
        $exceptions->renderable(function (AuthenticationException $e, $request) {
            return ApiResponse::error(__('messages.unauthenticated'), 401);
        });

        // 403 - không có quyền (can:user fail)
        $exceptions->renderable(function (AccessDeniedHttpException $e, $request) {
            return ApiResponse::error(__('messages.unauthorized'), 403);
        });

        // 429 - too many requests (nếu dùng throttle)
        $exceptions->renderable(function (ThrottleRequestsException $e, $request) {
            return ApiResponse::error(__('messages.too_many_requests'), 429);
        });

        // exception -> 500
        $exceptions->renderable(function (Throwable $e, $request) {
            Log::error($e);
            return ApiResponse::error(__('messages.error_bug'));
        });
    })->create();
