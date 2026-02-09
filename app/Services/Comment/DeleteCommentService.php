<?php

namespace App\Services\Comment;

use App\DTOs\Comment\DeleteCommentDTO;
use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteCommentService
{
    public function execute(DeleteCommentDTO $dto): void
    {
        $comment = Comment::where('id', $dto->commentId)
            ->where('post_id', $dto->postId)
            ->first();

        if (!$comment) {
            throw new ModelNotFoundException('Comment not found.');
        }

        $comment->delete();
    }
}
