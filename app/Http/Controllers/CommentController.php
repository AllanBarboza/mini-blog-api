<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(CreateCommentRequest $request)
    {
        $comment = Comment::create([
            'post_id' => $request->post_id,
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        return new CommentResource($comment);
    }
}
