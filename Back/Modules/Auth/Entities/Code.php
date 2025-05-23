<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = ['user_id', 'code', 'code_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
