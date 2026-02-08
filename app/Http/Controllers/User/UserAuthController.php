<?php

namespace App\Http\Controllers\User;

use App\DTOs\User\LoginUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginUserRequest;
use App\Services\User\LoginUserService;
use App\Http\Resources\AuthResource;
use Illuminate\Http\JsonResponse;

class UserAuthController extends Controller
{
    public function login(
        LoginUserRequest $request,
        LoginUserService $service
    ): JsonResponse {
        $dto = LoginUserDTO::fromArray($request->validated());

        $token = $service->execute($dto);

        return (new AuthResource($token))
            ->response()
            ->setStatusCode(200);
    }
}
