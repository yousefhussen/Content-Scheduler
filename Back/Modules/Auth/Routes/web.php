<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Entities\TempToken;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Controllers\AuthenticatedSessionController;
use Modules\Auth\Http\Controllers\EmailVerificationNotificationController;
use Modules\Auth\Http\Controllers\GoogleController;
use Modules\Auth\Http\Controllers\NewPasswordController;
use Modules\Auth\Http\Controllers\PasswordResetLinkController;
use Modules\Auth\Http\Controllers\RegisteredUserController;
use Modules\Auth\Http\Controllers\VerifyEmailController;
Route::prefix('auth')->group(function() {
    Route::get('/', 'AuthController@index');
});




Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

//change password
Route::post('/change-password', [NewPasswordController::class, 'changePassword'])
    ->middleware('auth')
    ->name('password.change');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware([ 'auth','signed', 'throttle:6,1'])
    ->name('verification.verify');



Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/user', [AuthenticatedSessionController::class, 'getUser'])->middleware('auth');


//serve profile picture
Route::get('/profile_pictures/{filename}', function ($filename) {
    $path = storage_path('app/public/profile_pictures/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->name('profile.picture');



Route::post('/verify-code', [\Modules\Auth\Http\Controllers\AuthController::class, 'verify']);


Route::get('/auth/google', [GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::post('/auth/exchange-token', function (Request $request) {
    $request->validate(['token' => 'required|string']);

    $tempToken = TempToken::with('user')
        ->where('token', $request->token)
        ->where('expires_at', '>', now())
        ->first();

    if (!$tempToken) {
        return response()->json(['error' => 'Invalid or expired token'], 401);
    }

    $authToken = $tempToken->user->createToken('auth_token')->plainTextToken;
    $tempToken->delete();

    return response()->json([
        'user' => $tempToken->user,
        'token' => $authToken
    ]);
})->middleware('cors');
