<?php

namespace Modules\ActivityLog\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ActivityLog\Entities\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(new \Modules\ActivityLog\Http\Resources\ActivityLogCollection($logs));

    }
}
