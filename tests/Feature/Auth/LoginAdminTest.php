<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginAdminTest extends TestCase
{
    use RefreshDatabase;


    public function test_admin_can_login_with_valid_credentials(): void
    {
        $payload = [
            'username' => fake()->userName(),
            'password' => fake()->password(8)
        ];

        $admin = Admin::factory()
            ->withUsername($payload['username'])
            ->withPassword($payload['password'])
            ->create();

        $response = $this->postJson('/api/admin/login', $payload);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['token']);


        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id'   => $admin->id,
            'tokenable_type' => Admin::class,
        ]);
    }

    public function test_admin_cannot_login_with_invalid_password(): void
    {
        $payload = [
            'username' => fake()->userName(),
            'password' => fake()->password(8)
        ];

        Admin::factory()
            ->withUsername($payload['username'])
            ->withPassword(fake()->password(8))
            ->create();

        $response = $this->postJson('/api/admin/login', $payload);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials.',
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_admin_cannot_login_with_invalid_username(): void
    {
        $payload = [
            'username' => fake()->userName(),
            'password' => fake()->password(8)
        ];

        Admin::factory()
            ->withUsername(fake()->userName())
            ->withPassword($payload['password'])
            ->create();


        $response = $this->postJson('/api/admin/login', $payload);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials.',
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
