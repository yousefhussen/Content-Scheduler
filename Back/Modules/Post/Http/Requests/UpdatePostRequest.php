<?php

namespace Modules\Post\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Modules\Post\Entities\Post;
use Modules\Post\Entities\PostUser;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules= [
            'title' => 'required|string|max:255',
            'message_content' => 'required|string',
            'image_url' => 'nullable|url',
            'scheduled_time' => 'nullable|date|date_format:Y-m-d H:i:s',
            'platforms' => 'required|array',
            'platforms.*' => [
                'exists:platforms,id',
                function ($attribute, $value, $fail) {
                    $user = PostUser::find(Auth::id());
                    if (!$user) {
                        $fail("User not found.");
                        return;
                    }
                    if (!$user->platforms()
                        ->where('platforms.id', $value)
                        ->where('platform_user.is_active', true) // Explicit pivot table reference
                        ->exists()) {
                        $fail("The selected platform $value is not active for the user. Or the user is not active on this platform.");
                    }
                },
            ],
        ];
        // Add platform-specific constraints
        foreach ($this->input('platforms', []) as $platform) {
            $constraints = Config::get("platforms.$platform");

            if (!$constraints) {
                continue;
            }

            // Text character limit
            if (isset($constraints['text']['character_limit'])) {
                $rules['message_content'] = "required|string|max:{$constraints['text']['character_limit']}";
            }

            // Image constraints
            if (isset($constraints['images'])) {
                $rules['images'] = "nullable|array|max:{$constraints['images']['max_count']}";
                $rules['images.*'] = "file|max:" . ($constraints['images']['max_size_mb'] * 1024);
            }

            // Video constraints
            if (isset($constraints['videos'])) {
                $rules['video'] = "nullable|file|max:" . ($constraints['videos']['max_size_mb'] * 1024);
            }

            // GIF constraints
            if (isset($constraints['gifs'])) {
                $rules['gif'] = "nullable|file|max:" . ($constraints['gifs']['max_size_mb'] * 1024);
            }
        }
        return $rules;
    }
}
