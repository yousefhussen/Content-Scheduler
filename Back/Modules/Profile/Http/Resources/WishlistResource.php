<?php

namespace Modules\Profile\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [

            'id' => $this->item_id,
            'name' => $this->item->name ?? null, // Assuming the item has a 'name' attribute
            'price' => $this->item->price ?? null, // Assuming the item has a 'price' attribute
            'image' => url($this->item->image ?? null), // Assuming the item has an 'image' attribute
            'added_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
