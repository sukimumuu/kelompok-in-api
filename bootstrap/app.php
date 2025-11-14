<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
         // Token invalid
        $exceptions->renderable(function (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid!',
            ], 401);
        });

        // Token expired
        $exceptions->renderable(function (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => 'Token sudah expired!',
            ], 401);
        });

        // Token not found
        $exceptions->renderable(function (\Tymon\JWTAuth\Exceptions\JWTException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan!',
            ], 401);
        });

        // Authentication failed
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau sudah expired!',
            ], 401);
        });
    })->create();
