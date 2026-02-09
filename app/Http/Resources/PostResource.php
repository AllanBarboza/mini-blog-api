<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            'published_at' => $this->published_at,
            'content' => $this->content,
            'comments_count' => $this->comments_count,
            'comments' => $this->whenLoaded('comments', function () {
                return $this->comments->map(fn($comment) => [
                    'content' => $comment->content,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                    ],
                ]);
            }),
            'author' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
