<?php

namespace Modules\Profile\Entities;

use Illuminate\Database\Eloquent\Model;

use Modules\Auth\Entities\User;
use Modules\Product\Entities\Item;

class wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
