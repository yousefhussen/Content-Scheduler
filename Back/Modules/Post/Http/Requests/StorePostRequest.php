<?php

namespace Modules\Post\Http\Requests;

use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Modules\Post\Entities\PostUser;
use Modules\Post\Rules\NotInThePast;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => Auth::id(),
        ]);
        if (!$this->has('status')) {

            $this->merge([
                'status' => 'draft'
            ]);
        }
        if ($this->has('scheduled_time') && $this->input('status') !== 'scheduled') {
            $this->merge([
                'status' => 'scheduled'
            ]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'message_content' => 'required|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'file|mimes:jpg,jpeg,png,gif|max:2048', // 2MB max for each image
            'status' => [
                'nullable',
                Rule::requiredIf(function () {
                    return !$this->input('scheduled_time');
                }),
                Rule::in(['draft', 'published', 'scheduled']),
            ],
            'scheduled_time' => [
                Rule::requiredIf(function () {
                    return $this->input('status') === 'scheduled';
                }),
                'date_format:Y-m-d H:i:s',
                new NotInThePast(),
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $userId = Auth::id();
                        $scheduledPostsToday = \Modules\Post\Entities\Post::where('user_id', $userId)
                            ->where('status', 'scheduled')
                            ->whereDate('created_at', now()->toDateString())
                            ->count();

                        if ($scheduledPostsToday >= 10) {
                            $fail("You can only schedule 10 posts per day.");
                        }
                    }
                },
            ],

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
                        ->where('platform_user.is_active', true)
                        ->exists()) {
                        $fail("The selected platform $value is not active for the user. Or the user is not active on this platform.");
                    }
                },
            ],
        ];

        // Retrieve platforms from the request
        $platforms = $this->input('platforms', []); // Explicitly fetch the platforms input

        // Apply platform-specific constraints
        foreach ($platforms as $platform) {
            $this->applyPlatformConstraints($rules, $platform);
        }



        return $rules;
    }

    protected function applyPlatformConstraints(array &$rules, string $platform): void
    {
//        what is passed is the platform id, not the name
        // Fetch the platform name from the database
        $platformName = \Modules\Post\Entities\Platform::find($platform);
        // Retrieve platform-specific constraints from the config

        $constraints = Config::get("post.{$platformName->type}");

        if (!$constraints) {
            return;
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
}
