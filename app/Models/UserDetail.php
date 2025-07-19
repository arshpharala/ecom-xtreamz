<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = ['user_id', 'mobile', 'country_id', 'dob', 'gender'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
