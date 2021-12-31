<?php

namespace Tests\Feature\API\v1\Thread;

use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\NewReplySubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSubscribe()
    {
        Sanctum::actingAs(User::factory()->create());
        $thread = Thread::factory()->create();
        $response = $this->postJson(route('subscribe', $thread->id));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['message' => 'user subscribed']);
    }

    public function testUnSubscribe()
    {
        Sanctum::actingAs(User::factory()->create());
        $thread = Thread::factory()->create();
        $response = $this->postJson(route('unsubscribe', $thread->id));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['message' => 'user unsubscribed']);
    }

    public function testSendNotification()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Notification::fake();
        $thread = Thread::factory()->create();
        $subscribeRespons = $this->post(route('subscribe', $thread));
        $subscribeRespons->assertSuccessful();
        $subscribeRespons->assertJson(['message' => 'user subscribed']);

        $answerResponse = $this->postJson(route('answers.store'), [
            'content' => 'answer',
            'thread_id' => $thread->id
        ]);
        $answerResponse->assertSuccessful();
        $answerResponse->assertJson(['message' => 'answer created']);
        Notification::assertSentTo($user, NewReplySubmitted::class);
    }
}
