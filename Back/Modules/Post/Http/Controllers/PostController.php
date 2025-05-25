<?php

namespace Modules\Post\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Post\Entities\Post;
use Modules\Post\Http\Requests\DeletePostRequest;
use Modules\Post\Http\Requests\StorePostRequest;
use Modules\Post\Http\Requests\UpdatePostRequest;
use Modules\Post\Http\Resources\PostCollection;
use Modules\Post\Http\Resources\PostResource;

class PostController extends Controller
{

    public function store(StorePostRequest $request)
    {
        //upload images if they exist seperate the urls by a comma
        $this->uploadImagesIfTheyExistSeperateTheUrlsByAComma($request);
        //request validation is handled by StorePostRequest
        $validatedData = $request->validated();

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $validatedData['title'],
            'message_content' => $validatedData['message_content'],
            'image_url' => $request->input('image_url', null),
            'status' => $validatedData['status'] ?? 'draft',
            'scheduled_time' => $validatedData['scheduled_time'] ?? null,
        ]);

        $post->platforms()->attach($request->platforms);

        return response()->json(['message' => 'Post created successfully', 'post' => PostResource::make($post)], 201);
    }


    // Update a scheduled post

    public function update(StorePostRequest $request, Post $post)
    {
        //upload images if they exist seperate the urls by a comma
        $this->uploadImagesIfTheyExistSeperateTheUrlsByAComma($request);
        // need to move this to a policy or a form request
        if ($post->user->id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->update($request->only(['title', 'content', 'image_url', 'scheduled_time']));

        if ($request->has('platforms')) {
            $post->platforms()->sync($request->platforms);
        }

        return response()->json(['message' => 'Post updated successfully', 'post' => PostResource::make($post)], 200);
    }

    // Get user's posts with filters (status, date)
    public function index(Request $request)
    {
        $query = Post::where('user_id', Auth::id())
            ->when($request->has('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->has('date'), fn($q) => $q->whereDate('created_at', $request->date))
            ->with('platforms');

        return $request->has('per_page')
            ? new PostCollection($query->paginate($request->per_page))
            : PostResource::collection($query->get());
    }


    public function destroy(DeletePostRequest $request, Post $post)
    {

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }


    public function getScheduledPosts(Request $request)
    {
        $scheduledPosts = Post::where('user_id', Auth::id())
            ->whereNotNull('scheduled_time')
            ->where('status', 'scheduled')
            ->orderBy('scheduled_time', 'asc')
            ->paginate(10);

        return new PostCollection($scheduledPosts);
    }

    public function publish(Post $post)
    {
        // need to move this to a policy or a form request
        if ($post->user->id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($post->status === 'published') {
            return response()->json(['message' => 'Post is already published'], 400);
        }

        $post->status = 'published';
        $post->published_at = Carbon::now();
        $post->save();

        return response()->json(['message' => 'Post published successfully', 'post' => PostResource::make($post)], 200);
    }

    public function serveImage($filename)
    {
        $path = storage_path('app/public/posts/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }


    public function uploadImagesIfTheyExistSeperateTheUrlsByAComma(StorePostRequest $request): void
    {
        //the urls should not have the http:// or https:// prefix nor the hostname
        if ($request->hasFile('images')) {
            $imageUrls = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('posts', 'public');
                $imageUrls[] = Storage::url($path); // Generates a relative URL
            }

            $request->merge(['image_url' => implode(',', $imageUrls)]);
        }
    }
}
