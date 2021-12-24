<?php

use App\Http\Controllers\API\v1\Channel\ChannelController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/')->group(function () {
    Route::prefix('channel')->group(function () {
        Route::get('/all', [ChannelController::class, 'getAllChannelsList'])->name('channel.all');
        Route::middleware('role_or_permission:channel management')->group(function () {
            Route::post('/create', [ChannelController::class, 'createNewChannel'])->name('channel.create');
            Route::put('/{channel}/edit', [ChannelController::class, 'editChannel'])->name('channel.edit');
            Route::delete('/{channel}', [ChannelController::class, 'deleteChannel'])->name('channel.delete');
        });
    });
});
