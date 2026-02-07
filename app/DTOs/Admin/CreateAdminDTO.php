<?php

namespace App\DTOs\Admin;

class CreateAdminDTO
{
    public function __construct(
        public string $name,
        public string $username,
        public string $password,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['name'],
            $data['username'],
            $data['password'],
        );
    }
}
