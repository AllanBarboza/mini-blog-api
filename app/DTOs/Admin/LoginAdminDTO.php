<?php

namespace App\DTOs\Admin;

class LoginAdminDTO
{
    public function __construct(
        public string $username,
        public string $password
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['username'],
            $data['password'],
        );
    }
}
