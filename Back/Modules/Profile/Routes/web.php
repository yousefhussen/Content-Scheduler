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


use Modules\Profile\Http\Controllers\WishlistController;

Route::prefix('profile')->group(function() {
    Route::get('/', 'ProfileController@index');

    Route::middleware('auth')->group(function () {
        Route::post('/wishlist', [WishlistController::class, 'store']);
        Route::get('/wishlist', [WishlistController::class, 'index']); // Retrieve wishlist items
        Route::delete('/wishlist/{itemId}', [WishlistController::class, 'destroy']); // Remove an item
        //get wishlist ids
        Route::get('/wishlist/ids', [WishlistController::class, 'getWishlistIds']);
    });
});
