<?php

use App\Models\CMS\Locale;
use App\Models\CMS\Country;
use Illuminate\Support\Str;
use App\Services\CartService;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        static $settings = null;

        if ($settings === null) {
            $settings = cache()->rememberForever('app_settings', function () {
                return \App\Models\Setting::pluck('value', 'key')->toArray();
            });
        }

        $value = $settings[$key] ?? $default;

        if (is_string($value) && Str::startsWith($value, ['settings/'])) {
            return 'storage/' . ltrim($value, '/');
        }

        return $value;
    }
}


if (!function_exists('cart')) {
    function cart()
    {
        return (new CartService())->get();
    }
}

if (!function_exists('cart_items_count')) {
    function cart_items_count()
    {
        return (new CartService())->getItemCount();
    }
}

if (!function_exists('locale')) {
    function locale()
    {
        return Locale::where('code', app()->getLocale())->first();
    }
}

if (!function_exists('active_locals')) {
    function active_locals()
    {
        return Locale::pluck('code')->toArray();
    }
}

if (!function_exists('active_currency')) {
    function active_currency()
    {
        return 'AED';
    }
}

if (!function_exists('active_country')) {
    function active_country()
    {
        return Country::first(); // for now send like this
    }
}

if (!function_exists('exchange_rate')) {
    /**
     * Get exchange rate from one currency to another.
     *
     * @param string $from
     * @param string $to
     * @return float
     */
    function exchange_rate($from, $to)
    {
        return 0.27; // AED to USD
    }
}

if (!function_exists('price_convert')) {
    /**
     * Convert price from one currency to another.
     *
     * @param mixed $amount
     * @param string $from
     * @param string $to
     * @return float
     */
    function price_convert($amount, $from, $to)
    {
        if ($from === $to) {
            return $amount;
        }

        $rate = exchange_rate($from, $to);
        return round($amount * $rate, 2);
    }
}
if (!function_exists('mask_sensitive')) {
    /**
     * Mask the sentive string
     *
     * @param mixed $value
     * @return string
     */
    function mask_sensitive(mixed $value): string
    {
        if (!$value) return '';
        $length = strlen($value);
        return substr($value, 0, 6) . str_repeat('*', max($length - 10, 4)) . substr($value, -4);
    }
}
