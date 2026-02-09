<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAdminTest extends TestCase
{
    use RefreshDatabase;


    public function test_admin_can_be_created(): void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ['*']
        );

        $payload = [
            'name' => fake()->name(),
            'username' => fake()->userName(),
            'password' => fake()->password(8)

        ];

        $response = $this->postJson('/api/admin', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonPath('name', $payload['name'])
            ->assertJsonPath('username', $payload['username'])
            ->assertJsonMissingPath('password')
            ->assertJsonStructure([
                'id',
                'name',
                'username',
                'created_at',
            ]);

        $this->assertDatabaseHas('admins', [
            'name' => $payload['name'],
            'username' => $payload['username'],
        ]);
    }


    public function test_password_is_hashed_when_admin_is_created(): void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ['*']
        );

        $payload = [
            'name' => fake()->name(),
            'username' => fake()->userName(),
            'password' => fake()->password(8)
        ];

        $this->postJson('/api/admin', $payload);

        $admin = Admin::where('username', $payload['username'])->first();

        $this->assertNotEquals($payload['password'], $admin->password);
        $this->assertTrue(
            Hash::check($payload['password'], $admin->password)
        );
    }

    public function test_username_must_be_unique(): void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ['*']
        );

        $username = fake()->userName();

        Admin::factory()->create([
            'username' => $username,
        ]);

        $response = $this->postJson('/api/admin', [
            'name' => fake()->name(),
            'username' => $username,
            'password' => fake()->password()
        ]);

        $response
            ->assertStatus(409)
            ->assertJsonValidationErrors(['username']);
    }

    public function test_name_username_and_password_are_required(): void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ['*']
        );

        $response = $this->postJson('/api/admin', []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'username',
                'password',
            ]);
    }

    public function test_guest_cannot_create_admin(): void
    {
        $payload = [
            'name' => fake()->name(),
            'username' => fake()->userName(),
            'password' => fake()->password(8)

        ];

        $response = $this->postJson('/api/admin', $payload);
        $response->assertStatus(401);
    }
    public function test_user_cannot_create_admin(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $payload = [
            'name' => fake()->name(),
            'username' => fake()->userName(),
            'password' => fake()->password(8)

        ];

        $response = $this->postJson('/api/admin', $payload);
        $response->assertStatus(403);
    }
}
