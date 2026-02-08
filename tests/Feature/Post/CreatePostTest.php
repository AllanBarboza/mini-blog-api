<?php

namespace Tests\Feature\Post;

use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_post(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $title = fake()->unique()->sentence(3);
        $content = fake()->paragraph();

        $payload = [
            'title'   => $title,
            'content' => $content,
        ];

        $response = $this->postJson('/api/posts', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonPath('title', $title)
            ->assertJsonPath('slug', Str::slug($title))
            ->assertJsonPath('status', 'draft')
            ->assertJsonMissingPath('user_id');

        $this->assertDatabaseHas('posts', [
            'title'   => $title,
            'slug'    => Str::slug($title),
            'user_id' => $user->id,
            'status'  => 'draft',
        ]);
    }

    public function test_admin_cannot_create_post(): void
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/posts', [
            'title'   => fake()->sentence(3),
            'content' => fake()->paragraph(),
        ]);

        $response->assertStatus(403);
        $response->assertJson(['message' => "This action is unauthorized."]);
    }

    public function test_guest_cannot_create_post(): void
    {
        $response = $this->postJson('/api/posts', [
            'title'   => fake()->sentence(3),
            'content' => fake()->paragraph(),
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => "Unauthenticated."]);
    }

    public function test_title_and_content_are_required(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/posts', []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title',
                'content',
            ]);
    }

    public function test_title_must_be_unique(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $title = fake()->unique()->sentence(3);

        Post::factory()->create([
            'title' => $title,
            'slug'  => Str::slug($title),
        ]);

        $response = $this->postJson('/api/posts', [
            'title'   => $title,
            'content' => fake()->paragraph(),
        ]);

        $response
            ->assertStatus(409);
        $response->assertJson(['message' => "Title already exists."]);
    }

    public function test_slug_is_generated_automatically(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        $title = fake()->unique()->sentence(4);

        $this->postJson('/api/posts', [
            'title'   => $title,
            'content' => fake()->paragraph(),
        ]);

        $this->assertDatabaseHas('posts', [
            'slug' => Str::slug($title),
        ]);
    }
}
