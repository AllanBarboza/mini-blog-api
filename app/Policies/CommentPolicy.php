<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function create($actor): bool
    {
        return $actor instanceof User;
    }

    public function delete($actor, Comment $comment): bool
    {
        if ($actor instanceof Admin) {
            return true;
        }

        if ($actor instanceof User) {
            return $comment->user_id === $actor->id;
        }

        return false;
    }

    public function view($actor = null, Comment $comment): bool
    {
        if ($actor instanceof Admin) {
            return true;
        }

        return $comment->user->banned_at === null;
    }
}
