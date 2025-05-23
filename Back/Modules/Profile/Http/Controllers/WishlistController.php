<?php

namespace Modules\Profile\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Profile\Entities\Wishlist;
use Modules\Product\Entities\Item;

class WishlistController extends Controller
{
    /**
     * Add an item to the user's wishlist.
     *
     * @param \Modules\Profile\Http\Requests\WishlistRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(\Modules\Profile\Http\Requests\WishlistRequest $request)
    {
        $user = $request->user(); // Get the authenticated user
        $itemId = $request->validated()['item_id']; // Get the validated item_id

        // Check if the item already exists in the user's wishlist
        $exists = Wishlist::where('user_id', $user->id)
            ->where('item_id', $itemId)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Item is already in your wishlist'], 409);
        }

        // Add the item to the wishlist
        Wishlist::create([
            'user_id' => $user->id,
            'item_id' => $itemId,
        ]);

        return response()->json(['message' => 'Item added to wishlist successfully'], 201);
    }

    public function index(Request $request)
    {
        $user = $request->user(); // Get the authenticated user

        // Retrieve wishlist items with related item details
        $wishlistItems = Wishlist::where('user_id', $user->id)
            ->with('item') // Assuming a relationship exists between Wishlist and Item
            ->get();

        return \Modules\Profile\Http\Resources\WishlistResource::collection($wishlistItems);
    }

    public function destroy(Request $request, $itemId)
    {
        $user = $request->user(); // Get the authenticated user

        // Find the wishlist entry for the user and item
        $wishlistItem = Wishlist::where('user_id', $user->id)
            ->where('item_id', $itemId)
            ->first();

        if (!$wishlistItem) {
            return response()->json(['message' => 'Item not found in your wishlist'], 404);
        }

        // Delete the wishlist entry
        $wishlistItem->delete();

        return response()->json(['message' => 'Item removed from wishlist successfully'], 200);
    }


    public function getWishlistIds(Request $request)
    {
        $user = $request->user(); // Get the authenticated user

        // Retrieve only the item IDs from the user's wishlist
        $wishlistIds = Wishlist::where('user_id', $user->id)
            ->pluck('item_id');

        return response()->json($wishlistIds);
    }
}
