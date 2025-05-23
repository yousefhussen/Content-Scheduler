<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempToken extends Model
{


    protected $fillable = [
        'token',
        'user_id',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for valid tokens
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
