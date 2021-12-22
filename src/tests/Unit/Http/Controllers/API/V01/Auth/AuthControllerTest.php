<?php

namespace Tests\Unit\Http\Controllers\API\V01\Auth;

// use PHPUnit\Framework\TestCase;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testRegister()
    {
        $response = $this->postJson('api/v1/auth/register');
        $response->assertStatus(422);
    }

    public function testRgisterNewUser()
    {
        $response = $this->postJson('api/v1/auth/register', [
            'name' => 'mohsen',
            'email' => 'mohsen@gmail.com',
            'password' => '01234567'
        ]);
        $response->assertStatus(201);
    }

    public function testLoginValidation()
    {
        $response = $this->postJson(route('auth.login'));
        $response->assertStatus(422);
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
        $response->assertStatus(200);
    }

    public function testUserIfLoggedIn()
    {
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->get(route('auth.user'));
        $response->assertStatus(200);
    }

    public function testLogOut()
    {
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->postJson(route('auth.logout'));
        $response->assertStatus(200);
    }
}
