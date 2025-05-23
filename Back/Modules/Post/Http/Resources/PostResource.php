<?php

namespace Modules\Post\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message_content' => $this->message_content,
            'image_url' => $this->image_url,
            'scheduled_time' => $this->scheduled_time,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'platforms' => $this->platforms->map(function ($platform) {
                return [
                    'id' => $platform->id,
                    'name' => $platform->name,
                ];
            }),
        ];
    }
}
