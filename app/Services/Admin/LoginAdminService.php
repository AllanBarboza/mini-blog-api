<?php

namespace App\Services\Admin;

use App\DTOs\Admin\LoginAdminDTO;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\InvalidCredentialsException;

class LoginAdminService
{
    public function execute(LoginAdminDTO $dto): string
    {
        $admin = Admin::where('username', $dto->username)->first();
        if (! $admin || ! Hash::check($dto->password, $admin->password)) {
            throw new InvalidCredentialsException();
        }

        $token = $admin->createToken(
            name: 'admin-token',
            abilities: ['admin']
        );

        return $token->plainTextToken;
    }
}
