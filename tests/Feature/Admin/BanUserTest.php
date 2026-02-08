<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


use Laravel\Sanctum\Sanctum;

class BanUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_ban_user(): void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ['*']
        );

        $user = User::factory()->create();

        $response = $this->patchJson("/api/admins/users/{$user->id}/ban");

        $response->assertStatus(204);

        $user->refresh();
        $this->assertNotNull($user->banned_at);
    }

    public function test_user_cannot_ban_other_user(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $user = User::factory()->create();

        $response = $this->patchJson("/api/admins/users/{$user->id}/ban");

        $response->assertStatus(403);

        $user->refresh();
        $this->assertNull($user->banned_at);
    }

    public function test_admin_cannot_ban_user_banned(): void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ['*']
        );

        $user = User::factory()
            ->banned()
            ->create();

        $originalBannedAt = $user->banned_at;

        $response = $this->patchJson("/api/admins/users/{$user->id}/ban");


        $response->assertStatus(409);
        $user->refresh();

        $this->assertEquals($originalBannedAt, $user->banned_at);
    }

    public function test_admin_cannot_ban_nonexistent_user(): void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ['*']
        );

        $nonExistentUserId = 9999;

        $response = $this->patchJson("/api/admins/users/{$nonExistentUserId}/ban");

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'User not found.',
        ]);
    }
}
