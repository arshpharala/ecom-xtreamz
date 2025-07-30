<?php

namespace App\Models\Cart;

use App\Models\Catalog\ProductVariant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'type',
        'value',
        'start_at',
        'end_at',
        'max_usage',
        'max_usage_per_user',
        'is_active',
        'min_cart_amount',
        'first_time_only'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
        'first_time_customer_only' => 'boolean',
    ];

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class,
            'coupon_product_variant',
            'coupon_id',
            'product_variant_id'
        );
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }
}
