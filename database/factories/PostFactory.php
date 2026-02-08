<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'status' => 'draft',
            'published_at' => null,
            'content' => fake()->paragraphs(3, true),
        ];
    }

    public function published(): static
    {
        return $this->state(function () {
            return [
                'status' => 'published',
                'published_at' => now(),
            ];
        });
    }

    public function archived(): static
    {
        return $this->state(function () {
            return [
                'status' => 'archived',
            ];
        });
    }
}
