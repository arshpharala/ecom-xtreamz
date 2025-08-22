<?php

namespace App\Repositories;

use App\Models\Catalog\Offer;

class OfferRepository
{

    public function getPromoOffers(int $limit = 3)
    {
        $locale = app()->getLocale();

        $offers = Offer::query()
            ->with(['translations' => fn($q) => $q->where('locale', $locale)])
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderBy('position')
            ->orderBy('ends_at') // soonest ending next
            ->limit($limit)
            ->get();

        return $offers->map(function ($o) {
            $title = optional($o->translation)->title ?? 'Offer';
            $eyebrow = optional($o->translation)->description ?? null;

            return (object)[
                'title' => $title,
                'eyebrow' => $eyebrow,  // small line
                'image' => $o->banner_image ? asset('storage/' . $o->banner_image) : asset('assets/images/default-promo.jpg'),
                'bg' => $o->bg_color ?: '#e6f7fc',
                'url' => $o->link_url ?: route('products', ['offer' => $o->id]), // fallback route
            ];
        });
    }
}
