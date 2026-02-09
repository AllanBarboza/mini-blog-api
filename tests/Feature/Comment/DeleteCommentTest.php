<?php

namespace Tests\Feature\Comment;

use App\Models\Admin;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_own_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->deleteJson(
            "/api/posts/{$post->id}/comments/{$comment->id}"
        );

        $response->assertStatus(204);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_user_cannot_delete_comment_from_another_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $otherUser->id,
            'post_id' => $post->id,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->deleteJson(
            "/api/posts/{$post->id}/comments/{$comment->id}"
        );

        $response->assertStatus(403);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_admin_can_delete_any_comment(): void
    {
        $admin = Admin::factory()->create();

        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
        ]);

        Sanctum::actingAs($admin, ['*']);

        $response = $this->deleteJson(
            "/api/posts/{$post->id}/comments/{$comment->id}"
        );

        $response->assertStatus(204);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_guest_cannot_delete_comment(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
        ]);

        $response = $this->deleteJson(
            "/api/posts/{$post->id}/comments/{$comment->id}"
        );

        $response->assertStatus(401);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_cannot_delete_nonexistent_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->deleteJson(
            "/api/posts/{$post->id}/comments/9999"
        );

        $response->assertStatus(404);
    }
}
