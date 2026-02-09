<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class ApiTestSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::factory()
            ->withUsername('admin')
            ->withPassword('password')
            ->create();

        $user = User::factory()
            ->withUsername('user')
            ->withPassword('password')
            ->create();

        $bannedUser = User::factory()
            ->withUsername('banned')
            ->withPassword('password')
            ->banned()
            ->create();

        $users = User::factory(5)->create();

        $users->each(function ($author) use ($users) {
            Post::factory(3)
                ->for($author)
                ->published()
                ->create()
                ->each(function ($post) use ($users) {
                    if (fake()->boolean()) {
                        Comment::factory(rand(1, 4))
                            ->for($post)
                            ->for($users->random())
                            ->create();
                    }
                });
        });

        $users->each(function ($author) {
            Post::factory(2)
                ->for($author)
                ->create();
        });

        $users->each(function ($author) {
            Post::factory(1)
                ->for($author)
                ->archived()
                ->create();
        });

        Post::factory(3)
            ->for($bannedUser)
            ->published()
            ->create();

        $this->command->info('API test data seeded successfully');
    }
}
