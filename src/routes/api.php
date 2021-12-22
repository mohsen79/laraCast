<?php

use App\Http\Controllers\API\v1\Auth\AuthController;
use App\Http\Controllers\API\v1\Channel\ChannelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1/')->group(function () {
    Route::prefix('/auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user'])->name('auth.user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
    Route::prefix('channel')->group(function () {
        Route::get('/all', [ChannelController::class, 'getAllChannelsList'])->name('channel.all');
        Route::post('/create', [ChannelController::class, 'createNewChannel'])->name('channel.create');
        Route::put('/{channel}/edit', [ChannelController::class, 'editChannel'])->name('channel.edit');
        Route::delete('/{channel}', [ChannelController::class, 'deleteChannel'])->name('channel.delete');
    });
});
