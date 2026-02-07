<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateAdminRequest;
use App\Http\Resources\AdminResource;
use App\Services\Admin\CreateAdminService;
use App\DTOs\Admin\CreateAdminDTO;

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
}
