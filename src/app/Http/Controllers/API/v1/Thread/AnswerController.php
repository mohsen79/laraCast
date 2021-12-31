<?php

namespace App\Http\Controllers\API\v1\Thread;

use App\Helpers\AnswerRefactory;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Subscribe;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\NewReplySubmitted;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class AnswerController extends Controller
{

    public function __construct()
    {
        $this->middleware('user-block');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $answers = resolve(AnswerRefactory::class)->getAllAnswers();
        return response()->json($answers, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required',
            'thread_id' => 'required'
        ]);
        resolve(AnswerRefactory::class)->store($data);
        $thread = Thread::find($data["thread_id"]);
        $users = Subscribe::where('thread_id', $data['thread_id'])->pluck('user_id');
        Notification::send(User::find($users), new NewReplySubmitted($thread));
        if (Thread::find($data["thread_id"])->user_id != auth()->user()) {
            auth()->user()->increment('score', 10);
        }
        return response()->json(['message' => 'answer created'], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        $data = $request->validate([
            'content' => 'required',
            'thread_id' => 'required'
        ]);
        if (Gate::forUser(auth()->user())->allows('manage-answer', $answer)) {
            resolve(AnswerRefactory::class)->update($data, $answer);
            return response()->json(['message' => 'answer updated'], Response::HTTP_OK);
        }
        return response()->json(['message' => 'access denied'], Response::HTTP_FORBIDDEN);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        if (Gate::forUser(auth()->user())->allows('manage-answer', $answer)) {
            resolve(AnswerRefactory::class)->destroy($answer);
            return response()->json(['message' => 'answer deleted successfuly'], Response::HTTP_OK);
        }
        return response()->json(['message' => 'access denied'], Response::HTTP_FORBIDDEN);
    }
}
