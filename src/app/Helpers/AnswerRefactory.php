<?php

namespace App\Helpers;

use App\Models\Answer;
use App\Models\Thread;

class AnswerRefactory
{
    public function getAllAnswers()
    {
        return Answer::query()->latest()->get();
    }

    public function store($data)
    {

        Thread::findOrFail($data['thread_id'])->answers()->create([
            'content' => $data['content'],
            'user_id' => auth()->user()->id
        ]);
    }

    public function update($data, Answer $answer)
    {
        $answer->update($data);
    }

    public function destroy($answer)
    {
        $answer->delete();
    }
}
