<?php

namespace Tests\Feature\Post;

use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_post(): void
    {
        $admin = Admin::factory()->create();
        $post = Post::factory()->create();

        Sanctum::actingAs($admin);

        $response = $this->deleteJson("/api/admin/posts/{$post->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_user_cannot_delete_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/admin/posts/{$post->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_guest_cannot_delete_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/admin/posts/{$post->id}");

        $response->assertStatus(401);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }
}
