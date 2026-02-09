<?php

namespace App\Services\Post;

use App\Models\Post;

class DeletePostService
{
    public function execute(Post $post): void
    {
        $post->delete();
    }
}
