<?php

namespace Modules\Post\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message_content',
        'image_url',
        'scheduled_time',
        'status',
        'user_id',
    ];

    // Relationship with Platform (many-to-many)
    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'post_platform')
            ->withPivot('platform_status')
            ->withTimestamps();
    }

    // Relationship with User
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
