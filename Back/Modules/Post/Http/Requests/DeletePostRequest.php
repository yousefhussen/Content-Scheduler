<?php

namespace Modules\Post\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DeletePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'post_id' => [
                'required',
                'exists:posts,id',
                function ($attribute, $value, $fail) {
                    $post = $this->route('post');
                    if ($post->status === 'published') {
                        $fail('Published posts cannot be deleted.');
                    }
                    if ($post->user->id !== Auth::id()) {
                        $fail('You are not authorized to delete this post.');
                    }
                },
            ],
            'user_id' => 'required|exists:users,id',
        ];
    }


    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => Auth::id(),
        ]);
        if ($this->route('post')) {
            $this->merge([
                'post_id' => $this->route('post')->id,
            ]);
        }
    }


}
