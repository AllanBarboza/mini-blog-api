<?php

namespace App\DTOs\Admin;


class BanUserDTO
{
    public function __construct(
        public string $userId
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['id']
        );
    }
}
