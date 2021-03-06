<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Http\Request;

class UserRefactory
{
    public function create($data): User
    {
        return User::create($data);
    }

    public function leaderBoard()
    {
        return User::query()->orderByDesc('score')->paginate(20);
    }
}
