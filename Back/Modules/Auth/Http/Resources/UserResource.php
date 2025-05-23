<?php

namespace Modules\Auth\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            //make the pp a link if it is not a url
            'profile_picture' => filter_var($this->profile_picture, FILTER_VALIDATE_URL) ? $this->profile_picture : url($this->profile_picture),

        ];
    }
}
