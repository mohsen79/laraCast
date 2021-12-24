<?php

namespace Tests\Feature\API\v1\Thread;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
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
        $thread = Thread::factory()->create();
        $response = $this->get(route('threads.show', $thread->slug));
        $response->assertStatus(Response::HTTP_OK);
    }
}
