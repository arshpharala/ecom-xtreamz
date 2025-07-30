<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGateway extends Model
{
    use SoftDeletes;

    protected $fillable = ['gateway', 'key', 'secret', 'additional', 'is_active'];

    protected $casts = [
        'additional' => 'array',
        'is_active' => 'boolean',
    ];
}
