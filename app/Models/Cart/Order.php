<?php

namespace App\Models\Cart;

use App\Models\User;
use App\Models\Address;
use App\Models\CMS\Currency;
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
        'external_reference',
        'currency_id',
        'sub_total',
        'tax',
        'total'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    // public function billingAddress()
    // {
    //     return $this->belongsTo(BillingAddress::class, 'billing_address_id');
    // }

    public function lineItems()
    {
        return $this->hasMany(OrderLineItem::class);
    }
}
