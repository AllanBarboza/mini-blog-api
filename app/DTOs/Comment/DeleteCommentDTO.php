<?php

namespace App\DTOs\Comment;

class DeleteCommentDTO
{
    public function __construct(
        public int $postId,
        public int $commentId,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['post_id'],
            $data['comment_id'],
        );
    }
}
