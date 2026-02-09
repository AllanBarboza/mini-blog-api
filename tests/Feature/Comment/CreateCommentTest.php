<?php

namespace Tests\Feature\Comment;

use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $payload = [
            'content' => fake()->paragraph(),
        ];

        $response = $this->postJson(
            "/api/posts/{$post->id}/comments",
            $payload
        );

        $response
            ->assertStatus(201)
            ->assertJsonPath('content', $payload['content'])
            ->assertJsonMissingPath('user_id')
            ->assertJsonMissingPath('post_id');

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => $payload['content'],
        ]);
    }

    public function test_guest_cannot_create_comment(): void
    {
        $post = Post::factory()->create();

        $response = $this->postJson(
            "/api/posts/{$post->id}/comments",
            [
                'content' => fake()->paragraph(),
            ]
        );

        $response->assertStatus(401);
    }

    public function test_admin_cannot_create_comment(): void
    {
        Sanctum::actingAs(Admin::factory()->create(), ['*']);

        $post = Post::factory()->create();

        $response = $this->postJson(
            "/api/posts/{$post->id}/comments",
            [
                'content' => fake()->paragraph(),
            ]
        );

        $response->assertStatus(403);
    }

    public function test_content_is_required(): void
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $post = Post::factory()->create();

        $response = $this->postJson(
            "/api/posts/{$post->id}/comments",
            []
        );

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }
}
