<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Entities\TempToken;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Requests\VerifyCodeRequest;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('auth::index');
    }

    public function verify(VerifyCodeRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $code = $user->codes()
                ->where('code', $request->code)
                ->where('code_type', $request->code_type)
                ->where('created_at', '>=', now()->subMinutes(10))
                ->first();

            if ($code) {
                if ($request->code_type == 'activation') {
                    $user->markEmailAsVerified();
                }

                return response()->json(['message' => 'Code successfully verified.'], 200);
            }
        }

        return response()->json(['message' => 'Invalid or expired code.'], 400);
    }


}
