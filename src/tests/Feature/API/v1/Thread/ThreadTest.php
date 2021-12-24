<?php

namespace Tests\Feature\API\v1\Thread;

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

    public function testThreadValidation()
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
}
