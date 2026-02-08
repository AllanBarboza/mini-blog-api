<?php

namespace App\Http\Controllers;

use App\DTOs\Post\CreatePostDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Resources\PostResource;
use App\Services\Post\CreatePostService;

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
}
