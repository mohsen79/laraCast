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

    public function update($request, Thread $thread)
    {

        if ($request->has('best_answer_id')) {
            $thread->update([
                'best_answer_id' => $request->best_answer_id
            ]);
        } else {
            $data = $request->validate([
                'title' => 'required',
                'slug' => '',
                'content' => 'required',
                'channel_id' => 'required|integer'
            ]);
            $thread->update($data);
        }
    }

    public function destroy(Thread $thread)
    {
        $thread->delete($thread);
    }
}
