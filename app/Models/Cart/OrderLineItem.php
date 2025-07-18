<?php

namespace App\Models\Cart;

use App\Models\Catalog\ProductVariant;
use Illuminate\Database\Eloquent\Model;

class OrderLineItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_variant_id',
        'quantity',
        'price',
        'subtotal'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
