<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Resources\UserResource;
use Modules\Auth\Mail\CodeMail;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validation for profile picture
        ]);

        $profilePicturePath = null;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {

            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_picture' => $profilePicturePath, // Save the file path
        ]);



        // Verification method
        if (config('auth.verify_using_code')) {
            $user->codes()->create([
                'code' => Str::upper(Str::random(4)),
                'code_type' => 'activation',
            ]);
            Mail::to($user->email)->send(new CodeMail($user));
        } else {
            event(new Registered($user));
        }


        Auth::login($user);

        return response()->json([
            'message' => 'User created successfully, please check your email for verification code',
            'data' => UserResource::make($user),
        ]);
    }
}
