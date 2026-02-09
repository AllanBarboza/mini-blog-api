<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create([
            'title' => 'Old Title',
            'content' => 'Old content',
            'status' => 'draft',
        ]);

        Sanctum::actingAs($user, ['*']);

        $payload = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => 'published',
        ];

        $response = $this->patchJson("/api/posts/{$post->id}", $payload);

        $response
            ->assertStatus(200)
            ->assertJsonPath('title', 'Updated Title')
            ->assertJsonPath('content', 'Updated content')
            ->assertJsonPath('status', 'published');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ]);
    }

    public function test_user_cannot_update_others_post(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $post = Post::factory()->for($otherUser)->create();

        Sanctum::actingAs($user, ['*']);

        $payload = [
            'title' => 'Hacked Title',
            'content' => 'Hacked content',
        ];

        $response = $this->patchJson("/api/posts/{$post->id}", $payload);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
            'title' => 'Hacked Title',
        ]);
    }

    public function test_guest_cannot_update_post(): void
    {
        $post = Post::factory()->create();

        $payload = [
            'title' => 'Unauthorized Edit',
            'content' => 'No way',
        ];

        $response = $this->patchJson("/api/posts/{$post->id}", $payload);

        $response->assertStatus(401);
    }

    public function test_update_requires_valid_fields(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        Sanctum::actingAs($user, ['*']);

        $payload = [
            'title' => '',
            'content' => '',
            'status' => 'invalid-status',
        ];

        $response = $this->patchJson("/api/posts/{$post->id}", $payload);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'content', 'status']);
    }
}
