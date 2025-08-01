<?php

namespace App\Services;

use App\Models\User;
use App\Models\Cart\Order;
use App\Models\Cart\Coupon;
use Illuminate\Support\Carbon;

class CouponService
{
    public function validateCoupon(string $code, float $cartTotal, $user = null, array $variantIds = [])
    {
        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();

        if (!$coupon) return ['valid' => false, 'message' => 'Invalid coupon'];

        $now = Carbon::now();
        if ($coupon->start_at && $now->lt($coupon->start_at)) {
            return ['valid' => false, 'message' => 'Invalid coupon'];
        }

        if ($coupon->end_at && $now->gt($coupon->end_at)) {
            return ['valid' => false, 'message' => 'Coupon has expired'];
        }

        if ($coupon->max_uses && $coupon->usages()->count() >= $coupon->max_uses) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached'];
        }

        if ($coupon->min_cart_amount && $cartTotal < $coupon->min_cart_amount) {
            return ['valid' => false, 'message' => 'Cart does not meet minimum amount'];
        }

        if ($coupon->first_time_customer_only && $user && Order::where('user_id', $user->id)->exists()) {
            return ['valid' => false, 'message' => 'Coupon only for first-time customers'];
        }

        if ($user && $coupon->max_uses_per_user) {
            $userUsages = $coupon->usages()->where('user_id', $user->id)->count();
            if ($userUsages >= $coupon->max_uses_per_user) {
                return ['valid' => false, 'message' => 'Coupon already used'];
            }
        }

        // If coupon applies to specific variants, ensure at least one matches
        if ($coupon->variants()->exists()) {
            $allowed = $coupon->variants()->pluck('product_variants.id')->toArray();
            if (!array_intersect($allowed, $variantIds)) {
                return ['valid' => false, 'message' => 'Coupon not applicable to selected products'];
            }
        }

        return ['valid' => true, 'coupon' => $coupon];
    }

    public function calculateDiscount(Coupon $coupon, float $cartTotal): float
    {
        if ($coupon->type === 'fixed') {
            return min($coupon->value, $cartTotal);
        }

        if ($coupon->type === 'percent') {
            return round($cartTotal * ($coupon->value / 100), 2);
        }

        return 0;
    }



    public function applyCoupon(string $code, float $cartTotal, $user = null, array $variantIds = [])
    {
        $validation = $this->validateCoupon($code, $cartTotal, $user, $variantIds);

        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }

        $coupon = $validation['coupon'];
        $discount = $this->calculateDiscount($coupon, $cartTotal);

        return [
            'success' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'final_total' => max($cartTotal - $discount, 0),
        ];
    }
}
