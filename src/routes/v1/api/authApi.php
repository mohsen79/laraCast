<?php

use App\Http\Controllers\API\v1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/')->group(function () {
    Route::prefix('/auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user'])->name('auth.user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
});
