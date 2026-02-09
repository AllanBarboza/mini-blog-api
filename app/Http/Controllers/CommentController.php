<?php

namespace App\Http\Controllers;

use App\DTOs\Comment\DeleteCommentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Services\Comment\DeleteCommentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function store(CreateCommentRequest $request)
    {
        $comment = Comment::create([
            'post_id' => $request->post_id,
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        return new CommentResource($comment);
    }

    public function destroy(
        DeleteCommentRequest $request,
        DeleteCommentService $service
    ) {
        $dto = DeleteCommentDTO::fromRequest($request->validated());

        $comment = Comment::findOrFail($dto->commentId);

        $this->authorize('delete', $comment);

        $service->execute($dto);

        return response()->noContent();
    }
}
