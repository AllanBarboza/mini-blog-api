<?php

namespace Tests\Feature\Post;

use App\Models\Admin;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_only_see_published_posts_from_non_banned_users(): void
    {
        $user = User::factory()->create();
        $bannedUser = User::factory()->create(['banned_at' => now()]);

        $visiblePost = Post::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Post::factory()->create([
            'user_id' => $user->id,
            'status' => 'draft',
        ]);

        Post::factory()->create([
            'user_id' => $bannedUser->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->getJson('/api/posts');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'id' => $visiblePost->id,
            ]);
    }

    public function test_authenticated_user_has_same_visibility_as_guest(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->getJson('/api/posts');

        $response
            ->assertOk()
            ->assertJsonFragment([
                'id' => $post->id,
            ]);
    }

    public function test_admin_can_see_posts_from_banned_users_and_any_status(): void
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        $bannedUser = User::factory()->create(['banned_at' => now()]);

        $draftPost = Post::factory()->create([
            'user_id' => $bannedUser->id,
            'status' => 'draft',
        ]);

        $response = $this->getJson('/api/posts');

        $response
            ->assertOk()
            ->assertJsonFragment([
                'id' => $draftPost->id,
            ]);
    }

    public function test_can_filter_posts_by_status(): void
    {
        $post = Post::factory()->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        Post::factory()->create(['status' => 'draft']);

        $response = $this->getJson('/api/posts?status=published');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'id' => $post->id,
            ]);
    }

    public function test_can_filter_posts_with_comments(): void
    {
        $postWithComment = Post::factory()->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        Comment::factory()->create([
            'post_id' => $postWithComment->id,
        ]);

        Post::factory()->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->getJson('/api/posts?has_comments=true');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'id' => $postWithComment->id,
            ]);
    }

    public function test_can_filter_posts_from_specific_user(): void
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Post::factory()->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->getJson("/api/posts?user_id={$user->id}");

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'id' => $post->id,
            ]);
    }

    public function test_can_filter_posts_commented_by_specific_user(): void
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/posts?commented_by_user={$user->id}");

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'id' => $post->id,
            ]);
    }

    public function test_can_filter_posts_by_date_range(): void
    {
        $insideRange = Post::factory()->create([
            'status' => 'published',
            'published_at' => now(),
            'created_at' => now()->subDays(2),
        ]);

        Post::factory()->create([
            'status' => 'published',
            'published_at' => now(),
            'created_at' => now()->subDays(10),
        ]);

        $response = $this->getJson('/api/posts?created_from=' . now()->subDays(3)->toDateString());

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'id' => $insideRange->id,
            ]);
    }

    public function test_results_are_paginated(): void
    {
        Post::factory()->count(20)->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->getJson('/api/posts?per_page=5');

        $response
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);
    }
}
