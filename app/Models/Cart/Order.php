<?php

namespace App\Models\Cart;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'billing_address_id',
        'email',
        'payment_method',
        'payment_status',
        'stripe_payment_intent_id',
        'total'
    ];

    public function billingAddress()
    {
        return $this->belongsTo(BillingAddress::class, 'billing_address_id');
    }

    public function lineItems()
    {
        return $this->hasMany(OrderLineItem::class);
    }
}
