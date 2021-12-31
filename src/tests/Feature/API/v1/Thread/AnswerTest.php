<?php

namespace Tests\Feature\API\v1\Thread;

use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetAllAnswersList()
    {
        $response = $this->get('/api/threads');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCreateAnswerValidation()
    {
        Sanctum::actingAs(User::factory()->create());
        $response = $this->postJson(route('answers.store'), []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['content', 'thread_id']);
    }

    public function testScore()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->create();
        $response = $this->postJson(route('answers.store'), [
            'content' => 'score',
            'thread_id' => $thread->id
        ]);
        $response->assertSuccessful();
        $user->refresh();
        $this->assertEquals(10, $user->score);
    }

    public function testCreateAnswer()
    {
        Sanctum::actingAs(User::factory()->create());
        $thread = Thread::factory()->create();
        $response = $this->postJson(route('answers.store'), [
            'content' => 'Answer',
            'thread_id' => $thread->id
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson(['message' => 'answer created']);
        $this->assertTrue($thread->answers()->where('content', 'Answer')->exists());
    }

    public function testUpdateAnswerValidation()
    {
        Sanctum::actingAs(User::factory()->create());
        $answer = Answer::factory()->create();
        $response = $this->putJson(route('answers.update', $answer->id), []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['content', 'thread_id']);
    }

    public function testUpdateAnswer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $answer = Answer::factory()->create(['user_id' => $user->id]);
        $thread = Thread::factory()->create();
        $response = $this->putJson(route('answers.update', $answer->id), [
            'content' => 'Update Answer',
            'thread_id' => $thread->id
        ]);
        $answer->refresh();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['message' => 'answer updated']);
        $this->assertEquals('Update Answer', $answer->content);
    }

    public function testDestroyAnswer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $answer = Answer::factory()->create(['user_id' => $user->id]);
        $response = $this->deleteJson(route('answers.destroy', $answer->id));
        $response->assertJson(['message' => 'answer deleted successfuly']);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertNull(Answer::find($answer->id));
    }
}
