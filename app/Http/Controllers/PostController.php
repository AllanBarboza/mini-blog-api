<?php

namespace App\Http\Controllers;

use App\DTOs\Post\CreatePostDTO;
use App\DTOs\Post\ListPostDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\ListPostRequest;
use App\Http\Resources\PostResource;
use App\Services\Post\CreatePostService;
use App\Services\Post\ListPostService;

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
}
