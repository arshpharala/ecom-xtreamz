<?php

namespace App\Models\Cart;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'province', 'city', 'area', 'address', 'landmark'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
