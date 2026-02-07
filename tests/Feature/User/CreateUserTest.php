<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $payload = [
            'name' => fake()->name(),
            'username' => fake()->userName(),
            'password' => fake()->password(8),
        ];

        $response = $this->postJson('/api/users', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.name', $payload['name'])
            ->assertJsonPath('data.username', $payload['username'])
            ->assertJsonPath('data.bio', null)
            ->assertJsonMissingPath('data.password')
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'username',
                    'bio',
                    'created_at',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $payload['name'],
            'username' => $payload['username'],
        ]);
    }


    public function test_password_is_hashed_when_user_is_created(): void
    {
        $payload = [
            'name' => fake()->name,
            'username' => fake()->userName(),
            'password' => fake()->password(8),
        ];

        $this->postJson('/api/users', $payload);

        $user = User::where('username', $payload['username'])->first();

        $this->assertNotEquals($payload['password'], $user->password);
        $this->assertTrue(
            Hash::check($payload['password'], $user->password)
        );
    }

    public function test_username_must_be_unique(): void
    {
        $username = fake()->userName();

        User::factory()->create([
            'username' => $username,
        ]);

        $response = $this->postJson('/api/users', [
            'name' => fake()->name(),
            'username' => $username,
            'password' => fake()->password()
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['username']);
    }

    public function test_name_username_and_password_are_required(): void
    {
        $response = $this->postJson('/api/users', []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'username',
                'password',
            ]);
    }
}
