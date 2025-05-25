<?php

namespace Modules\Post\Console;

use Illuminate\Console\Command;
use Modules\Post\Database\Seeders\PostSeeder;

class SeedPostsCommand extends Command
{
    protected $signature = 'posts:make {userId : The ID of the user}';
    protected $description = 'Seed posts for a specific user';

    public function handle()
    {
        $userId = $this->argument('userId');
        $this->info("Seeding posts for user ID: $userId");

        $seeder = new PostSeeder($userId);
        $seeder->run();

        $this->info('Posts seeded successfully!');
    }
}
