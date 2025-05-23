<?php

use Illuminate\Support\Facades\Route;
use Modules\Post\Http\Controllers\PlatformController;
use Modules\Post\Http\Controllers\PostController;




Route::prefix('posts')->middleware('auth')->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::post('/', [PostController::class, 'store']);
    //change to put
    Route::post('/{post}', [PostController::class, 'update']);
    Route::delete('/{post}', [PostController::class, 'destroy']);
});

Route::prefix('platforms')->middleware('auth')->group(function () {
    Route::get('/', [PlatformController::class, 'index']); // Get all platforms
    Route::get('/user', [PlatformController::class, 'getUserPlatforms']); // Get platforms for the authenticated user
    Route::post('/SetStatus', [PlatformController::class, 'SetStatus']); // Toggle platform status for the user
});

