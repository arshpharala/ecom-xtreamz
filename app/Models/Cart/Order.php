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
        'reference_number',
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
        'total',
        'email_sent'
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->reference_number)) {
                $order->reference_number = static::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';

        $lastOrder = static::withTrashed()
            ->where('reference_number', 'like', "$prefix-%")
            ->orderByDesc('reference_number')
            ->first();

        $nextNumber = 1;

        if ($lastOrder && preg_match('/ORD-(\d+)$/', $lastOrder->reference_number, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }

        return $prefix . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

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

    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    function scopeWithJoins($query)
    {
        return $query->leftJoin('addresses', 'addresses.id', 'orders.billing_address_id')
            ->leftJoin('users', 'users.id', 'orders.user_id')
            ->leftJoin('currencies', 'currencies.id', 'orders.currency_id');
    }
}
