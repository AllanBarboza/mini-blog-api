<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $post = $this->route('post');
        return $post && $this->user()->can('update', $post);
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'content' => ['sometimes', 'string'],
            'status' => ['sometimes', 'in:draft,published,archived'],
        ];
    }
}
