<?php

namespace App\Http\Controllers\API\v1\Tread;

use App\Helpers\ThreadRefactory;
use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = resolve(ThreadRefactory::class)->getAvailableThreads();
        return response()->json($threads, Response::HTTP_OK);
    }

    public function show($slug)
    {
        $thread = resolve(ThreadRefactory::class)->getThreadBySlug($slug);
        return response()->json($thread, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'slug' => '',
            'content' => 'required',
            'channel_id' => 'required|integer'
        ]);
        resolve(ThreadRefactory::class)->store($data);
        return response()->json(['message' => 'thread created successfuly'], Response::HTTP_CREATED);
    }
}
