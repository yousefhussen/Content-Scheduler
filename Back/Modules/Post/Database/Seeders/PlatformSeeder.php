<?php

namespace Modules\Post\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Post\Entities\Platform;

class PlatformSeeder extends Seeder
{
    public function run()
    {
        $platforms = [
            ['name' => 'Twitter', 'type' => 'twitter'],
            ['name' => 'Instagram', 'type' => 'instagram'],
            ['name' => 'LinkedIn', 'type' => 'linkedin'],
            ['name' => 'Facebook', 'type' => 'facebook'],
        ];

        foreach ($platforms as $platform) {
            Platform::updateOrCreate(['type' => $platform['type']], $platform);
        }
    }
}
