<?php

namespace App\Http\Controllers\API\v1\Subscribe;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscribeController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-block');
    }

    public function subscribe(Thread $thread)
    {
        auth()->user()->subscribes()->create(['thread_id' => $thread->id]);
        return response()->json(['message' => 'user subscribed'], Response::HTTP_OK);
    }

    public function unSbscribe(Thread $thread)
    {
        Subscribe::query()->where(['thread_id' => $thread->id, 'user_id' => auth()->user()->id])->delete();
        return response()->json(['message' => 'user unsubscribed'], Response::HTTP_OK);
    }
}
