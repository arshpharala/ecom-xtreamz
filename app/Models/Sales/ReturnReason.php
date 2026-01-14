<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnReason extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reason',
        'is_active',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
