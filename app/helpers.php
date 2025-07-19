<?php

use App\Models\CMS\Locale;
use App\Services\CartService;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return \App\Models\Setting::where('key', $key)->value('value') ?? $default;
    }
}


if (!function_exists('cart_items_count')) {
    function cart_items_count(){
        return (new CartService())->getItemCount();
    }
}

if (!function_exists('active_locals')) {
    function active_locals(){
        return Locale::pluck('code')->toArray();
    }
}

if (!function_exists('active_currency')) {
    function active_currency(){
        return 'AED';
    }
}
