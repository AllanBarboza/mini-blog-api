<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\Admin\BanUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateAdminRequest;
use App\Http\Resources\AdminResource;
use App\Services\Admin\CreateAdminService;
use App\DTOs\Admin\CreateAdminDTO;
use App\Http\Requests\Admin\BanUserRequest;
use App\Services\Admin\BanUserService;

class AdminController extends Controller
{
    public function store(
        CreateAdminRequest $request,
        CreateAdminService $service
    ) {
        $dto = CreateAdminDTO::fromRequest($request->validated());
        $admin = $service->execute($dto);

        return (new AdminResource($admin))
            ->response()
            ->setStatusCode(201);
    }

    public function banUser(
        BanUserRequest $request,
        BanUserService $service
    ) {
        $dto = BanUserDTO::fromRequest($request->validated());
        $service->execute($dto);

        return response([], 204);
    }
}
