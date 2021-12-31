<?php

namespace App\Http\Controllers\API\v1\User;

use App\Helpers\UserRefactory;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function userNotifications(User $user)
    {
        return response()->json(auth()->user()->id->unreadNotification(), Response::HTTP_OK);
    }

    public function leaderboard()
    {
        return resolve(UserRefactory::class)->leaderBoard();
    }
}
