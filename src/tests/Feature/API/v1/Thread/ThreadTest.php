<?php

namespace Tests\Feature\API\v1\Thread;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAllThreadListShouldBeAccessable()
    {
        $response = $this->get(route('threads.index'));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testAllThreadListShouldBeAccessableBySlug()
    {
        Sanctum::actingAs(User::factory()->create());
        $thread = Thread::factory()->create();
        $response = $this->get(route('threads.show', $thread->slug));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCreateThreadValidation()
    {
        $response = $this->postJson(route('threads.store', []));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCreateThread()
    {
        Sanctum::actingAs(User::factory()->create());
        $thread = Thread::factory()->make()->toArray();
        $response = $this->postJson(route('threads.store'), $thread);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testUpdateThreadValidation()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->create(['user_id' => $user->id]);
        $response = $this->putJson(route('threads.update', $thread->id), []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateThread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $updateThread = Thread::factory()->make()->toArray();
        $thread = Thread::factory()->create(['user_id' => $user->id]);
        $response = $this->putJson(route('threads.update', $thread->id), $updateThread);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testUpdateBestAnswerIdThread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $updateThread = ['best_answer_id' => 1];
        $thread = Thread::factory()->create(['user_id' => $user->id]);
        $response = $this->putJson(route('threads.update', $thread->id), $updateThread);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testDestroyThread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->create(['user_id' => $user->id]);
        $response = $this->deleteJson(route('threads.destroy', $thread->id));
        $response->assertStatus(Response::HTTP_OK);
    }
}
