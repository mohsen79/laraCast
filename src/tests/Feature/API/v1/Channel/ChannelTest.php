<?php

namespace Tests\Feature\API\v1\Channel;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function registerRolesAndPermissions()
    {
        if (Role::whereName(config('permission.default_roles')[0])->count() < 1) {
            foreach (config('permission.default_roles') as $role) {
                Role::create([
                    'name' => $role
                ]);
            }
        }
        if (Permission::whereName(config('permission.default_permissions')[0])->count() < 1) {
            foreach (config('permission.default_permissions') as $permission) {
                Permission::create([
                    'name' => $permission
                ]);
            }
        }
    }

    public function testGetAllChannelsList()
    {
        $response = $this->get(route('channel.all'));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCreateNewChannelValidation()
    {
        $this->registerRolesAndPermissions();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('channel management');
        $response = $this->postJson(route('channel.create'), []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCreateNewChannel()
    {
        $this->registerRolesAndPermissions();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('channel management');
        $response = $this->postJson(route('channel.create'), [
            'name' => 'Laravel'
        ]);

        $response->assertStatus(201);
    }

    public function testEditChannelValidation()
    {
        $this->registerRolesAndPermissions();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('channel management');
        $channel = Channel::factory()->create();
        $response = $this->json('PUT', route('channel.edit', $channel->id), []);
        $response->assertStatus(422);
    }

    public function testEditChannel()
    {
        $this->registerRolesAndPermissions();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('channel management');
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
        $this->registerRolesAndPermissions();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('channel management');
        $channel = Channel::factory()->create();
        $response = $this->json('DELETE', route('channel.delete', $channel->id));
        $response->assertStatus(200);
    }
}
