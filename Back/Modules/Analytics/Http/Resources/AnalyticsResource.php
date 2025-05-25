<?php

namespace Modules\Analytics\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnalyticsResource extends JsonResource
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
            'posts_per_platform' => $this->posts_per_platform,
            'publishing_success_rate' => round($this->publishing_success_rate, 2),
            'scheduled_count' => $this->scheduled_count,
            'published_count' => $this->published_count,
            'draft_count' => $this->draft_count,
            'scheduled_today' => $this->scheduled_today,
        ];
    }
}
