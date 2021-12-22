<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Http\Request;

class UserRefactory
{
    public function create($data)
    {
        User::create($data);
    }
}
