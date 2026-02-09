<?php

namespace App\Services\Post;

use App\DTOs\Post\ListPostDTO;
use App\Models\Admin;
use App\Models\Post;

class ListPostService
{
    public function execute(ListPostDTO $dto)
    {
        $query = Post::query()
            ->with(['user'])
            ->latest();

        if ($dto->includeComments) {
            $query->with('comments');
        }

        if (!$dto->actor || !$dto->actor instanceof Admin) {
            $query->published()
                ->whereHas(
                    'user',
                    fn($q) =>
                    $q->whereNull('banned_at')
                );
        }

        if ($dto->status) {
            $query->where('status', $dto->status);
        }

        if ($dto->userId) {
            $query->where('user_id', $dto->userId);
        }

        if ($dto->hasComments !== null) {
            $dto->hasComments
                ? $query->has('comments')
                : $query->doesntHave('comments');
        }

        if ($dto->commentedByUser) {
            $query->whereHas(
                'comments',
                fn($q) =>
                $q->where('user_id', $dto->commentedByUser)
            );
        }

        if ($dto->createdFrom) {
            $query->whereDate('created_at', '>=', $dto->createdFrom);
        }

        if ($dto->createdTo) {
            $query->whereDate('created_at', '<=', $dto->createdTo);
        }

        $query->withCount('comments');

        return $query->paginate($dto->perPage);
    }
}
