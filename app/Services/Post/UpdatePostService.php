<?php

namespace App\Services\Post;

use App\DTOs\Post\UpdatePostDTO;
use App\Models\Post;

class UpdatePostService
{
    public function execute(UpdatePostDTO $dto, Post $post): Post
    {
        $post->update([
            'title' => $dto->title,
            'content' => $dto->content,
            'status' => $dto->status,
        ]);

        return $post;
    }
}
