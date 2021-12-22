<?php

namespace Tests\Unit\API\v1\Channel;

use App\Models\Channel;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetAllChannelsList()
    {
        $response = $this->get(route('channel.all'));
        $response->assertStatus(200);
    }

    public function testCreateNewChannelValidation()
    {
        $response = $this->postJson(route('channel.create'));
        $response->assertStatus(422);
    }

    public function testCreateNewChannel()
    {
        $response = $this->postJson(route('channel.create'), [
            'name' => 'Laravel'
        ]);

        $response->assertStatus(201);
    }

    public function testEditChannelValidation()
    {
        $channel = Channel::factory()->create();
        $response = $this->json('PUT', route('channel.edit', $channel->id), []);
        $response->assertStatus(422);
    }

    public function testEditChannel()
    {
        $channel = Channel::factory()->create([
            'name' => 'React'
        ]);
        $response = $this->json('PUT', route('channel.edit', $channel->id), [
            'name' => 'Laravel'
        ]);
        $this->assertEquals('React', $channel->name);
        $response->assertStatus(200);
    }

    public function testdeleteChannel()
    {
        $channel = Channel::factory()->create();
        $response = $this->json('DELETE', route('channel.delete', $channel->id));
        $response->assertStatus(200);
    }
}
