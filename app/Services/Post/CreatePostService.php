<?php

namespace App\Services\Post;

use App\DTOs\Post\CreatePostDTO;
use App\Models\Post;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CreatePostService
{
    public function execute(CreatePostDTO $dto): Post
    {
        if (Post::where('title', $dto->title)->exists()) {
            throw new ConflictHttpException('Title already exists.');
        }

        return Post::create([
            'title'   => $dto->title,
            'slug'    => Str::slug($dto->title),
            'content' => $dto->content,
            'status'  => 'draft',
            'user_id' => $dto->userId,
        ]);
    }
}
