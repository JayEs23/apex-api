<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and assigned to the "api"
| middleware group. Enjoy building your API!
|
*/

// Authentication Routes
Route::post('/authentication/register', [AuthController::class, 'register']);
Route::post('/authentication/login', [AuthController::class, 'login']);

// Authenticated Routes
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [AuthController::class, 'profile']);

    // User Profile Routes
    Route::prefix('profile')->group(function () {
        Route::put('/update', [UserController::class, 'updateProfile']);
        Route::put('/password', [UserController::class, 'updatePassword']);
    });

    // Admin User Management Routes
    Route::prefix('admin/users')->group(function () {
        Route::post('/', [AdminController::class, 'create']);
        Route::get('/', [AdminController::class, 'index']);
        Route::put('/{id}', [AdminController::class, 'update']);
        Route::delete('/{id}', [AdminController::class, 'destroy']);
    });
});
