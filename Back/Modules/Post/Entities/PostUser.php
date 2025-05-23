<?php

namespace Modules\Post\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\User as BaseUser;

class PostUser extends BaseUser
{
    //table name
    protected $table = 'users';
    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'platform_user', 'user_id', 'platform_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }
}
