<?php

namespace Tests\Unit\API\v1\Auth;

// use PHPUnit\Framework\TestCase;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function RegisterRolesAndPermissions()
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

    public function testRegister()
    {
        $response = $this->postJson('api/v1/auth/register');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testRgisterNewUser()
    {
        $this->RegisterRolesAndPermissions();
        $response = $this->postJson('api/v1/auth/register', [
            'name' => 'mohsen',
            'email' => 'mohsen@gmail.com',
            'password' => '01234567'
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testLoginValidation()
    {
        $response = $this->postJson(route('auth.login'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testLogIn()
    {
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->postJson(route('auth.login'), [
                'email' => $user->email,
                'password' => 'password'
            ]);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testUserIfLoggedIn()
    {
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->get(route('auth.user'));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testLogOut()
    {
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->postJson(route('auth.logout'));
        $response->assertStatus(Response::HTTP_OK);
    }
}
