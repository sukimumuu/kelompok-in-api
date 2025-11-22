<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\ClassroomController;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::middleware(['role:teacher'])->group(function () {
            // Classroom Routes
            Route::get('/classrooms', [ClassroomController::class, 'index']);
            Route::post('/create-class', [ClassroomController::class, 'store']);

            // Project Routes
            Route::get('/projects', [ProjectController::class, 'index']);
            Route::post('/create-project', [ProjectController::class, 'store']);
        });

        Route::middleware(['role:student'])->group(function () {
            // Join Classroom Route
            Route::post('/join-class', [ClassroomController::class, 'joinClass']);
        });
    });

});
