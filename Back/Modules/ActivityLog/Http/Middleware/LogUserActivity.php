<?php

namespace Modules\ActivityLog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\ActivityLog;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log only if the response is successful (status code 2xx)
        if (Auth::check() && $response->isSuccessful()) {
            $method = $request->method();
            $path = $request->path();

            // Load loggable actions from config
            $loggableActions = config('activitylog.loggable_actions');
            $action = $this->getAction($method, $path, $loggableActions);

            if ($action) {
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => $action,
                    'ip_address' => $request->ip(),
                ]);
            }
        }

        return $response;
    }

    protected function getAction(string $method, string $path, array $loggableActions): ?string
    {
        if (isset($loggableActions[$method])) {
            foreach ($loggableActions[$method] as $route => $action) {
                $pattern = preg_replace('/\{[^}]+\}/', '[^/]+', $route);
                if (preg_match("#^{$pattern}$#", $path)) {
                    return $action;
                }
            }
        }

        return null;
    }
}
