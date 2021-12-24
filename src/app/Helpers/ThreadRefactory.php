<?php

namespace App\Helpers;

use App\Models\Thread;

class ThreadRefactory
{
    public function getAvailableThreads()
    {
        return Thread::whereFlag('1')->latest()->get();
    }

    public function getThreadBySlug($slug)
    {
        return Thread::whereSlug($slug)->whereFlag(1)->first();
    }

    public function store($data)
    {
        auth()->user()->threads()->create($data);
    }
}
