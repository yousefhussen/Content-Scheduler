<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Entities\TempToken;
use Modules\Auth\Entities\User;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'consent'])
            ->redirectUrl(config('services.google.redirect'))
            ->redirect();
    }

    public function callback(Request $request)
    {
        try {


            $googleUser = Socialite::driver('google')->user();
            //check if the user is already registered

            $existingUser = User::where('email', $googleUser->getEmail())->first();
            if ($existingUser) {
                // User already exists
                //add the google_id to the user
                $existingUser->google_id = $googleUser->getId();
                \Auth::login($existingUser);
                return redirect()->away(
                    config('app.frontend_url') . '/'
                );
            }
            else {

                // User does not exist, create a new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)), // Generate a random password
                    'profile_picture' => $googleUser->getAvatar(),
                ]);
                \Auth::login($user);
            }

            return redirect()->away(
                config('app.frontend_url') . '/'
            );

        } catch (\Exception $e) {

            \Log::error('Google Auth Error: ' . $e->getMessage());

            return redirect()->away(
                config('app.frontend_url') . '/?error=' . urlencode($e->getMessage())
            );
        }
    }
}
