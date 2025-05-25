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
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Http\Resources\UserResource;
use Modules\Auth\Mail\CodeMail;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $profilePicturePath = null;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {

            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }
        $valaidatedData = $request->validated();
        $user = User::create([
            'name' => $valaidatedData['name'],
            'email' =>  $valaidatedData['email'],
            'password' => Hash::make($valaidatedData['password']),
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
            if (config('auth.verify_user_email')) {
                event(new Registered($user));
            }

        }


        Auth::login($user);

        return response()->json([
            'message' => 'User created successfully, please check your email for verification code',
            'data' => UserResource::make($user),
        ]);
    }
}
