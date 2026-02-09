<?php

namespace App\Http\Controllers;

use App\DTOs\Post\CreatePostDTO;
use App\DTOs\Post\ListPostDTO;
use App\DTOs\Post\UpdatePostDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\DeletePostRequest;
use App\Http\Requests\Post\ListPostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\Post\CreatePostService;
use App\Services\Post\DeletePostService;
use App\Services\Post\ListPostService;
use App\Services\Post\UpdatePostService;

class PostController extends Controller
{
    public function store(
        CreatePostRequest $request,
        CreatePostService $service
    ) {
        $dto = CreatePostDTO::fromRequest(
            $request->validated(),
            $request->user()->id
        );

        $post = $service->execute($dto);

        return (new PostResource($post))
            ->response()
            ->setStatusCode(201);
    }

    public function list(
        ListPostRequest $request,
        ListPostService $service
    ) {
        $dto = ListPostDTO::fromRequest(
            $request->validated(),
            $request->user()
        );

        $posts = $service->execute($dto);

        return PostResource::collection($posts);
    }

    public function update(UpdatePostRequest $request, UpdatePostService $service, Post $post)
    {
        $dto = UpdatePostDTO::fromRequest($request->validated());

        $updatedPost = $service->execute($dto, $post);

        return new PostResource($updatedPost);
    }

    public function destroy(DeletePostRequest $request, Post $post, DeletePostService $service)
    {
        $service->execute($post);

        return response()->json([], 204);
    }
}
