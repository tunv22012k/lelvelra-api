<?php

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // validate -> 422
        $exceptions->renderable(function (ValidationException $e, $request) {
            return ApiResponse::validation($e->errors());
        });

        // exception -> 500
        $exceptions->renderable(function (Throwable $e, $request) {
            return ApiResponse::error(_('error_bug'));
        });
    })->create();
