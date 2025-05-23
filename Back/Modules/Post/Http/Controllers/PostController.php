<?php

namespace Modules\Post\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Post\Entities\Post;
use Modules\Post\Http\Requests\StorePostRequest;
use Modules\Post\Http\Requests\UpdatePostRequest;
use Modules\Post\Http\Resources\PostCollection;
use Modules\Post\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePostRequest $request)
    {

        $status = $request->scheduled_time ? 'scheduled' : 'draft';

        $post = Post::create([
            'title' => $request->title,
            'message_content' => $request->message_content,
            'image_url' => $request->image_url,
            'scheduled_time' => $request->scheduled_time,
            'status' => $status,
            'user_id' => Auth::id(),
        ]);

        $post->platforms()->attach($request->platforms);

        return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);
    }


    // Update a scheduled post

    public function update(UpdatePostRequest $request, Post $post)
    {

        // need to move this to a policy or a form request
        if ($post->user->id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->update($request->only(['title', 'content', 'image_url', 'scheduled_time']));

        if ($request->has('platforms')) {
            $post->platforms()->sync($request->platforms);
        }

        return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
    }

    // Get user's posts with filters (status, date)
    public function index(Request $request)
    {
        $query = Post::where('user_id', Auth::id());

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $posts = $query->with('platforms')->paginate(10);

        return new PostCollection($posts);
    }




    // Delete a post
    public function destroy(Post $post)
    {
        // need to move this to a policy or a form request
        if ($post->user->id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
