<?php

namespace App\Helpers;

use App\Models\Channel;

class ChannelRefactory
{
    public function create($data)
    {
        Channel::create($data);
    }
}
