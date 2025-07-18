<?php

namespace App\Models\Cart;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    protected $fillable = ['user_id', 'card_last_four', 'card_brand', 'expiry_month', 'expiry_year', 'card_token', 'gateway'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
