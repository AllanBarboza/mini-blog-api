<?php

namespace App\Services\Admin;

use App\DTOs\Admin\BanUserDTO;
use App\Exceptions\ConflictException;
use App\Exceptions\UserAlreadyBannedException;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BanUserService
{
    public function execute(BanUserDTO $dto)
    {
        $user = User::find($dto->userId);

        if (!$user) {
            throw new ModelNotFoundException('User not found.');
        }
        if ($user->banned_at) {
            throw new ConflictException('User already banned.');
        }

        $user->ban();

        return $user;
    }
}
