<?php

namespace App\DTOs\User;


class LoginUserDTO
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
