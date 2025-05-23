<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Resources\UserResource;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Credentials Not Matching'], 401);
        }
        if (Auth::guard('web')) {
            $request->authenticate();

            $request->session()->regenerate();

            return response()->json(['message' => 'User logged in successfully']);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
//    public function store(LoginRequest $request): \Illuminate\Http\JsonResponse
//    {
//        $request->authenticate();
//
//        $request->session()->regenerate();
//
//        return response()->json(['message' => 'User logged in successfully']);
//    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(['message' => 'User logged out successfully']);
    }

    public function getUser(Request $request)
    {
        $user = Auth::user();

        return new UserResource($user);
    }
}
