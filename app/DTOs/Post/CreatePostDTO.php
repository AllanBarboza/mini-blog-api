<?php

namespace App\DTOs\Post;

class CreatePostDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content,
        public readonly int $userId,
    ) {}

    public static function fromRequest(array $data, int $userId): self
    {
        return new self(
            title: $data['title'],
            content: $data['content'],
            userId: $userId,
        );
    }
}
