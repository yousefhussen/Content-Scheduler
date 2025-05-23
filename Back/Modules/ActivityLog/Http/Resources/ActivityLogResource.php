<?php

namespace Modules\ActivityLog\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user ? $this->user->name : 'Guest',
            'action' => $this->action,
//            'details' => $this->details,
            'ip_address' => $this->ip_address,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
