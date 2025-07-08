<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return \App\Models\Setting::where('key', $key)->value('value') ?? $default;
    }
}


if (!function_exists('active_locals')) {
    function active_locals(){
        return config('app.supported_locales');
    }
}
