<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimesheetController;

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:api')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('users', UserController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('timesheets', TimesheetController::class);

    Route::post('logout', [AuthController::class, 'logout']);
});
