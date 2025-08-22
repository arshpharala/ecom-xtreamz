<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use App\Models\Cart\Coupon;
use App\Services\CouponService;

class CartService
{
    protected $sessionKey = 'cart';

    public function get(): Collection
    {
        $items         = $this->getItems();
        $subTotal      = $this->getSubTotal();
        $discount      = $this->getDiscount();
        $tax           = $this->getTax();
        $total         = $this->getTotal();

        return collect([
            'items'     => $items,
            'subTotal'  => $subTotal,
            'discount'  => $discount,
            'tax'       => $tax,
            'total'     => $total,
            'subTotal_with_currency'  => price_format(active_currency(), $subTotal),
            'discount_with_currency'  => price_format(active_currency(), $discount),
            'tax_with_currency'       => price_format(active_currency(), $tax),
            'total_with_currency'     => price_format(active_currency(), $total),
            'count'     => $this->getItemCount(),
            'coupon'    => $this->getCoupon(),
        ]);
    }

    function getTax(): float
    {
        $subTotal      = $this->getSubTotal(); // 500
        $discount      = $this->getDiscount(); // 10% of 500 = 50
        $discountedSub = max($subTotal - $discount, 0); // 500 - 50 = 450
        $tax           = $this->getTaxOnAmount($discountedSub); // 5% of 450 = 22.5

        return (float) $tax;
    }

    public function getSubTotal(): float
    {
        return array_sum(array_column($this->getItems(), 'subtotal'));
    }

    public function getDiscount(): float
    {
        $data = $this->getCoupon();
        return $data['discount'] ?? 0;
    }

    public function getTaxOnAmount(float $amount): float
    {
        $taxRate = setting('tax_rate', 5); // Assume 5% default
        return round($amount * ($taxRate / 100), 2);
    }

    public function getTotal(): float
    {
        $subTotal = $this->getSubTotal();
        $discounted = max($subTotal - $this->getDiscount($subTotal), 0);
        return $discounted + $this->getTaxOnAmount($discounted);
    }

    public function getItems(): array
    {
        return Session::get($this->sessionKey, []);
    }

    public function getItem(string $variantId): ?array
    {
        return $this->getItems()[$variantId] ?? null;
    }

    public function add(string $variantId, int $qty = 1, float $price = null, array $options = []): void
    {
        $cart = $this->getItems();

        if (isset($cart[$variantId])) {
            $cart[$variantId]['qty'] += $qty;
        } else {
            $cart[$variantId] = [
                'qty'     => $qty,
                'price'   => $price,
                'options' => $options,
            ];
        }

        $cart[$variantId]['subtotal'] = $cart[$variantId]['price'] * $cart[$variantId]['qty'];

        $this->save($cart);
    }

    public function update(string $variantId, int $qty): void
    {
        $cart = $this->getItems();

        if (isset($cart[$variantId])) {
            $cart[$variantId]['qty'] = $qty;
            $cart[$variantId]['subtotal'] = $cart[$variantId]['price'] * $qty;
            $this->save($cart);
        }
    }

    public function remove(string $variantId): void
    {
        $cart = $this->getItems();
        unset($cart[$variantId]);
        $this->save($cart);
    }

    public function clear(): void
    {
        Session::forget($this->sessionKey);
        $this->removeCoupon();
    }

    protected function save(array $cart): void
    {
        Session::put($this->sessionKey, $cart);
    }

    public function getItemCount(): int
    {
        return array_sum(array_column($this->getItems(), 'qty'));
    }


    public function applyCoupon(string $code, $user = null): array
    {
        $items = $this->getItems();
        $variantIds = array_keys($items);
        $cartTotal = $this->getSubTotal();

        $result = app(CouponService::class)->applyCoupon($code, $cartTotal, $user, $variantIds);

        if ($result['success']) {
            Session::put('applied_coupon', [
                'code'      => $result['coupon']->code,
                'id'        => $result['coupon']->id,
                'discount'  => $result['discount'],
                'type'      => $result['coupon']->type,
                'value'     => $result['coupon']->value,
            ]);
        }

        return $result;
    }

    public function removeCoupon(): void
    {
        Session::forget('applied_coupon');
    }

    public function getCoupon(): ?array
    {
        return Session::get('applied_coupon');
    }

    public function hasCoupon(): bool
    {
        return Session::has('applied_coupon');
    }

    public function refresh(): ?string
    {
        $items = $this->getItems();
        $subTotal = $this->getSubTotal();

        if ($this->hasCoupon()) {
            $couponData = $this->getCoupon();
            $coupon = \App\Models\Cart\Coupon::find($couponData['id']);

            if (!$coupon) {
                $this->removeCoupon();
                return 'Coupon was removed: no longer valid.';
            }

            if (empty($items)) {
                $this->removeCoupon();
                return 'Coupon was removed: your cart is empty.';
            }

            if ($subTotal < ($coupon->min_cart_amount ?? 0)) {
                $this->removeCoupon();
                return 'Coupon was removed: subtotal is below the required minimum.';
            }
        }

        return null; // no issues
    }
}
