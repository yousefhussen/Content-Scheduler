<?php

namespace Modules\Post\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Post\Entities\Platform;
use Modules\Post\Entities\PostUser;
use Modules\Post\Http\Requests\TogglePlatformRequest;

class PlatformController extends Controller
{
    public function index()
    {
        $platforms = Platform::all();
        return response()->json($platforms);
    }


    public function getUserPlatforms()
    {
        // Use PostUser model to get the authenticated user
        $user = PostUser::find(Auth::id());

        // Get platforms for the authenticated user
        $platforms = $user->platforms()->get();

        return response()->json($platforms);
    }

    public function SetStatus(TogglePlatformRequest $request)
    {

        //Use PostUser model to get the authenticated user
        $user = PostUser::find(Auth::id());
        $platformId = $request->platform_id;

        // Attach or update the pivot table
        $user->platforms()->syncWithoutDetaching([
            $platformId => ['is_active' => $request->is_active],
        ]);

        return response()->json(['message' => 'Platform status updated successfully']);
    }
}
