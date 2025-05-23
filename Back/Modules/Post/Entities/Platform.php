<?php

namespace Modules\Post\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    // Relationship with Post (many-to-many)
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_platform')
            ->withPivot('platform_status')
            ->withTimestamps();
    }
}
