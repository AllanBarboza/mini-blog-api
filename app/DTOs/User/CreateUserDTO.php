<?php

namespace App\DTOs\User;

class CreateUserDTO
{
    public function __construct(
        public string $name,
        public string $username,
        public string $password,
        public ?string $biography = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['name'],
            $data['username'],
            $data['password'],
            $data['biography'] ?? null,
        );
    }
}
