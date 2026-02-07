<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginAdminRequest;
use App\Services\Admin\LoginAdminService;
use App\DTOs\Admin\LoginAdminDTO;
use Illuminate\Http\JsonResponse;

class AdminAuthController extends Controller
{
    public function login(
        LoginAdminRequest $request,
        LoginAdminService $service
    ): JsonResponse {
        $dto = LoginAdminDTO::fromArray($request->validated());

        $token = $service->execute($dto);

        return response()->json([
            'token' => $token,
        ]);
    }
}
