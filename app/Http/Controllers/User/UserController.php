<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\User\CreateUserService;
use App\DTOs\User\CreateUserDTO;

class UserController extends Controller
{
    public function store(
        StoreUserRequest $request,
        CreateUserService $service
    ) {
        $dto = CreateUserDTO::fromRequest($request->validated());
        $user = $service->execute($dto);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }
}
