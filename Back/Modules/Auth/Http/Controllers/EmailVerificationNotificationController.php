<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {


        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 200);
        }
        if (config('auth.verify_using_code')) {
            $expirationMinutes = config('auth.code_expiration_minutes', 10); // Default to 10 minutes if not set in config

            $existingCode = $request->user()->codes()
                ->where('code_type', 'activation')
                ->where('created_at', '>=', now()->subMinutes($expirationMinutes))
                ->first();

            if (!$existingCode) {
                $request->user()->codes()->create([
                    'code' => Str::upper(Str::random(4)),
                    'code_type' => 'activation',
                ]);
            }

            return response()->json(['message' => 'verification code sent']);
        }
        else{
            $request->user()->sendEmailVerificationNotification();
            return response()->json(['message' => 'verification link sent']);
        }
    }
}
