<?php

namespace App\Helpers;

use App\Models\Channel;

class ChannelReFactory
{
    public function create($data)
    {
        Channel::create($data);
    }
}
