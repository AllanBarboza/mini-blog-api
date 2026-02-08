<?php

namespace App\Services\User;

use App\Models\User;
use App\DTOs\User\LoginUserDTO;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\UserBannedException;
use Illuminate\Support\Facades\Hash;

class LoginUserService
{
    public function execute(LoginUserDTO $dto): string
    {
        $user = User::where('username', $dto->username)->first();
        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        if ($user->banned_at !== null) {
            throw new UserBannedException();
        }

        $token = $user->createToken(
            name: 'admin-token',
            abilities: ['admin']
        );

        return $token->plainTextToken;
    }
}
