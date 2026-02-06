<?php

namespace App\Services\User;

use App\DTOs\User\CreateUserDTO;
use App\Models\User;

class CreateUserService
{
    public function execute(CreateUserDTO $dto): User
    {
        return User::create([
            'name'     => $dto->name,
            'username' => $dto->username,
            'password' => $dto->password,
            'biography'      => $dto->biography,
        ]);
    }
}
