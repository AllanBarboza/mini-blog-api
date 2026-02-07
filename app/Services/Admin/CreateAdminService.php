<?php

namespace App\Services\Admin;

use App\DTOs\Admin\CreateAdminDTO;
use App\Models\Admin;

class CreateAdminService
{
    public function execute(CreateAdminDTO $dto): Admin
    {
        return Admin::create([
            'name'     => $dto->name,
            'username' => $dto->username,
            'password' => $dto->password
        ]);
    }
}
