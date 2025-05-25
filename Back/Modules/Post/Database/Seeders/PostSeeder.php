<?php

namespace Modules\Post\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Modules\Post\Entities\Platform;
use Modules\Post\Entities\Post;
use Modules\Post\Entities\PostUser;

class PostSeeder extends Seeder
{
    private ?int $userId;

    public function __construct(?int $userId = null)
    {
        $this->userId = $userId;
    }

    public function run()
    {
        $userId = $this->userId ?? auth()->id();

        // Ensure user_id is not null
        if (!$userId) {
            throw new \Exception('A valid user ID must be provided for seeding posts.');
        }
        //make links with platforms and user for all platforms and active platforms
        $user = PostUser::find($userId);
        if (!$user) {
            throw new \Exception("User with ID $userId not found.");
        }

        $allPlatforms = Platform::all()->pluck('id')->toArray();
        foreach ($allPlatforms as $platformId) {
            if (!$user->platforms()->where('platform_id', $platformId)->exists()) {
                $user->platforms()->attach($platformId, ['is_active' => true]);
            }
            else {
                $user->platforms()->updateExistingPivot($platformId, ['is_active' => true]);
            }
        }
        Post::factory(50)
            ->state(['user_id' => $userId]) // Pass the user_id to the factory
            ->create()
            ->each(function ($post) use ($userId) {
                $post->status = $this->getRandomStatus();
                $post->scheduled_time = $post->status === 'scheduled'
                    ? Carbon::now()->addDays(rand(1, 30))->format('Y-m-d H:i:s')
                    : null;





                $post->save();
            });
    }

    private function getRandomStatus()
    {
        $statuses = ['draft', 'published', 'scheduled'];
        return $statuses[array_rand($statuses)];
    }
}
