<?php

namespace App\DTOs\Post;

class UpdatePostDTO
{
    public function __construct(
        public string $title,
        public string $content,
        public string $status
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            $validated['title'],
            $validated['content'],
            $validated['status']
        );
    }
}
