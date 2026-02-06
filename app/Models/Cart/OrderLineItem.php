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
        'subtotal',
        'options'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function attachments()
    {
        return $this->morphMany(\App\Models\Attachment::class, 'attachable');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function returnItems()
    {
        return $this->hasMany(\App\Models\Sales\ReturnRequestItem::class);
    }

    public function getReturnedQuantity(): int
    {
        return (int) $this->returnItems()
            ->whereHas('returnRequest', function ($query) {
                $query->whereNotIn('status', ['rejected']);
            })
            ->sum('quantity');
    }

    public function getReturnableQuantity(): int
    {
        return $this->quantity - $this->getReturnedQuantity();
    }
}
