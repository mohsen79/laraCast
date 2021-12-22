<?php

namespace App\Helpers;

use App\Models\Channel;

class ChannelFactory
{
    public function create($data)
    {
        Channel::create($data);
    }
}
