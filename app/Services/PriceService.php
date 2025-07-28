<?php

namespace App\Services;

use App\Models\Catalog\ProductVariant;

class PriceService
{
    public static function calculateDiscountedPrice(ProductVariant $variant): array
    {
        $price = $variant->price;
        $offer = $variant->activeOffer();

        if (!$offer) {
            return [
                'final_price'     => $price,
                'discount_amount' => 0,
                'original_price'  => $price,
                'offer_id'        => null,
            ];
        }

        $discount = 0;
        if ($offer->discount_type === 'percent') {
            $discount = $price * ($offer->discount_value / 100);
        } elseif ($offer->discount_type === 'fixed') {
            $discount = $offer->discount_value;
        }

        return [
            'final_price'     => max(0, $price - $discount),
            'discount_amount' => $discount,
            'original_price'  => $price,
            'offer_id'        => $offer->id,
        ];
    }
}
