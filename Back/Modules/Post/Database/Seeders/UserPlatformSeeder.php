<?php

namespace Modules\Post\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Post\Entities\Platform;
use Modules\Post\Entities\PostUser;

class UserPlatformSeeder extends Seeder
{
    public function run()
    {
        $users = PostUser::all();
        $platforms = Platform::all();

        foreach ($users as $user) {
            foreach ($platforms as $platform) {
                $user->platforms()->attach($platform->id, [
                    'is_active' => rand(0, 1),
                ]);
            }
        }
    }
}
