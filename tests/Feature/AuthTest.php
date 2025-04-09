<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Teszt Elek',
            'email' => 'teszt@example.com',
            'gender' => 'male',  
            'birthday' => '2000-01-01',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['user', 'token']);

        $this->assertDatabaseHas('users', [
            'email' => 'teszt@example.com',
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'teszt@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'teszt@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['user', 'token']);
    }

    public function test_login_fails_with_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'teszt@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'teszt@example.com',
            'password' => 'rosszpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJsonPath('errors.email.0', 'The provided credentials are inconrrect.');
    }
}
