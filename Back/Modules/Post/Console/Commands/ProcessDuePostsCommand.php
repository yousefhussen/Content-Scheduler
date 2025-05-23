<?php

namespace Modules\Post\Console\Commands;

use Illuminate\Console\Command as CommandBase;
use Modules\Post\Entities\Post;
use Symfony\Component\Console\Command\Command;

class ProcessDuePostsCommand extends CommandBase
{
    protected $signature = 'process:due-posts';

    protected $description = 'Process posts that are due for publishing or other actions';

    public function handle()
    {
        // Fetch posts that are due
        $duePosts = Post::where('scheduled_time', '<=', now())
            ->where('status', 'scheduled')
            ->get();

        foreach ($duePosts as $post) {

            $post->status = 'published';
            $post->published_at = now();
            $post->save();

            $this->info("Processed post ID: {$post->id}");
        }
        $this->comment('Processing due posts...');
        $this->info('All due posts have been processed.');
        return Command::SUCCESS;
    }
}
