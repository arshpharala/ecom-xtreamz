<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasUlids, SoftDeletes;

    public $incrementing    = false;
    protected $keyType      = 'string';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'website',
        'logo',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    function scopeActive($builder)
    {
        $builder->where('vendors.is_active', 1);
    }
}
