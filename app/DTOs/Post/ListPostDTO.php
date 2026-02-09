<?php

namespace App\DTOs\Post;

class ListPostDTO
{
    public function __construct(
        public ?string $status,
        public ?int $userId,
        public ?bool $hasComments,
        public ?int $commentedByUser,
        public ?string $createdFrom,
        public ?string $createdTo,
        public int $perPage,
        public $actor,
    ) {}

    public static function fromRequest(array $data, $actor): self
    {
        return new self(
            $data['status'] ?? null,
            $data['user_id'] ?? null,
            $data['has_comments'] ?? null,
            $data['commented_by_user'] ?? null,
            $data['created_from'] ?? null,
            $data['created_to'] ?? null,
            $data['per_page'] ?? 15,
            $actor
        );
    }
}
