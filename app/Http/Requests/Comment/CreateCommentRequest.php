<?php

namespace App\Http\Requests\Comment;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Comment::class);
    }

    public function rules(): array
    {
        return [
            'post_id' => ['required', 'exists:posts,id'],
            'content' => ['required', 'string', 'min:3'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'post_id' => $this->route('id'),
        ]);
    }
}
