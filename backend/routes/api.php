<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ProfileController;

Route::get('/test', function () {
    return response()->json(['status' => 'API is working']);
});

Route::get('/hello', function () {
    return response()->json([
        'message' => 'Hello from Laravel!',
        'timestamp' => now()
    ]);
});

Route::get('/', function () {
    return response()->json([
        'status' => 'Laravel API is running',
        'version' => app()->version()
    ]);
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::post('/verify-email', [UserController::class, 'verifyEmail']);
Route::post('/email/verification-notification', [UserController::class, 'resendVerification'])
    ->name('verification.send');

Route::middleware('auth.token')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);

    // Admin routes
    Route::middleware('permission:manage-roles')->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::post('/users/{user}/roles', [RoleController::class, 'assignUserRoles']);
    });
});
