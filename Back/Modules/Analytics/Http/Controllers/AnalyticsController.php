<?php

namespace Modules\Analytics\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Post\Entities\Post;

class AnalyticsController extends Controller
{
    public function index(): JsonResponse
    {
        $userId = Auth::id();

        // Posts grouped by platform
        $postsPerPlatform = Post::where('user_id', $userId)
            ->with('platforms')
            ->get()
            ->groupBy(fn($post) => $post->platforms->pluck('type')->toArray())
            ->map(fn($posts) => count($posts));

        // Convert the grouped data to a more readable format

        // Publishing success rate
        $totalPosts = Post::where('user_id', $userId)->count();
        $publishedPosts = Post::where('user_id', $userId)->where('status', 'published')->count();
        $successRate = $totalPosts > 0 ? ($publishedPosts / $totalPosts) * 100 : 0;

        // Scheduled vs Published counts vs draft
        $scheduledCount = Post::where('user_id', $userId)->where('status', 'scheduled')->count();
        $publishedCount = $publishedPosts;
        $draftCount = Post::where('user_id', $userId)->where('status', 'draft')->count();

        // created posts today
        $scheduledToday = Post::where('user_id', $userId)
            ->where('status', 'scheduled')
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return response()->json([
            'posts_per_platform' => $postsPerPlatform,
            'publishing_success_rate' => round($successRate, 2),
            'scheduled_count' => $scheduledCount,
            'published_count' => $publishedCount,
            'draft_count' => $draftCount,
            'scheduled_today' => $scheduledToday,
        ]);
    }
}
