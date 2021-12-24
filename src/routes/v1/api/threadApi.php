<?php

use App\Http\Controllers\API\v1\Tread\ThreadController;
use Illuminate\Support\Facades\Route;

Route::resource('/threads', ThreadController::class);
