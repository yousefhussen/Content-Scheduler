<?php

namespace Modules\ActivityLog\Entities;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'details', 'ip_address'];


    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
