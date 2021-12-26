<?php

namespace App\Http\Controllers\API\v1\Thread;

use App\Helpers\ThreadRefactory;
use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

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

    public function update(Request $request, Thread $thread)
    {
        if (Gate::forUser(auth()->user())->allows('manage-thread', $thread)) {
            resolve(ThreadRefactory::class)->update($request, $thread);
            return response()->json(['message' => 'thread created successfuly'], Response::HTTP_OK);
        }
        return response()->json(['message' => 'access denied'], Response::HTTP_FORBIDDEN);
    }

    public function destroy(Thread $thread)
    {
        if (Gate::forUser(auth()->user())->allows('manage-thread', $thread)) {
            resolve(ThreadRefactory::class)->destroy($thread);
            return response()->json(['message' => 'thread deleted successfuly'], Response::HTTP_OK);
        }
        return response()->json(['message' => 'access denied'], Response::HTTP_FORBIDDEN);
    }
}
