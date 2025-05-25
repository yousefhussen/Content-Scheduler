<?php

namespace Modules\Post\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {

        return [
            'data' => $this->collection->map(function ($post) {
                return new PostResource($post);
            }),
            'meta' => [
                'total' => $this->total() ?? 0,
                'count' => $this->count() ?? 0,
                'per_page' => $this->perPage() ?? 0,
                'current_page' => $this->currentPage() ?? 0,
                'last_page' => $this->lastPage() ?? 0,
            ],
        ];
    }
}
