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
        'status',
        'external_reference',
        'currency_id',
        'sub_total',
        'tax',
        'total',
        'email_sent',
        'delivered_at'
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
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

    function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
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

    public function returnRequests()
    {
        return $this->hasMany(\App\Models\Sales\ReturnRequest::class);
    }

    public function canBeReturned(): bool
    {
        // 1. Must be paid
        if ($this->payment_status !== 'paid') {
            return false;
        }

        // 2. Check days restriction (default 7 days if not set)
        $policyDays = (int) (\App\Models\Setting::get('return_policy_days', 7));

        // Use delivered_at if available, otherwise fallback to updated_at (proxy for delivery/payment)
        $referenceDate = $this->delivered_at ?? $this->updated_at;

        if ($referenceDate->addDays($policyDays)->isPast()) {
            return false;
        }

        // 3. Check if all items are already returned (optional but good for UI)
        // We'll handle item-level restriction in the wizard
        return true;
    }

    function scopeWithJoins($query)
    {
        return $query->leftJoin('addresses', 'addresses.id', 'orders.billing_address_id')
            ->leftJoin('users', 'users.id', 'orders.user_id')
            ->leftJoin('currencies', 'currencies.id', 'orders.currency_id');
    }
}
