<?php

namespace Modules\Post\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Post\Entities\Post;
use Carbon\Carbon;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['draft', 'published', 'scheduled']);
        $scheduledTime = $status === 'scheduled' ? Carbon::now()->addDays(rand(1, 30))->format('Y-m-d H:i:s') : null;

        return [
            'title' => $this->faker->sentence,
            'message_content' => $this->faker->paragraph,
            'image_url' => $this->faker->optional()->imageUrl(),
            'scheduled_time' => $scheduledTime,
            'status' => $status,
            'user_id' => null, // To be set when creating the post
        ];
    }
}
