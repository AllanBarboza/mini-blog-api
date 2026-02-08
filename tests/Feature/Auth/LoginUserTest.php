<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $payload = [
            'username' => fake()->userName(),
            'password' => fake()->password(8)
        ];

        $user = User::factory()
            ->withUsername($payload['username'])
            ->withPassword($payload['password'])
            ->create();

        $response = $this->postJson('/api/login', $payload);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token',
                ],
                'message',
            ]);;

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id'   => $user->id,
            'tokenable_type' => User::class,
        ]);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $payload = [
            'username' => fake()->userName(),
            'password' => fake()->password(8)
        ];

        User::factory()
            ->withUsername($payload['username'])
            ->withPassword(fake()->password(8))
            ->create();

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => __('auth.invalid_credentials'),
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_user_cannot_login_with_invalid_username(): void
    {
        $payload = [
            'username' => fake()->userName(),
            'password' => fake()->password(8)
        ];

        User::factory()
            ->withUsername(fake()->userName())
            ->withPassword($payload['password'])
            ->create();

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => __('auth.invalid_credentials'),
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_banned_user_cannot_login(): void
    {
        $payload = [
            'username' => fake()->userName(),
            'password' => fake()->password(8)
        ];

        User::factory()
            ->withUsername($payload['username'])
            ->withPassword($payload['password'])
            ->banned()
            ->create();

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(403);
    }
}
