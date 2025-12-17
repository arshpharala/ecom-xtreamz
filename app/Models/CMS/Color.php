<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'name',
        'hex_code',
        'is_active',
    ];
}
