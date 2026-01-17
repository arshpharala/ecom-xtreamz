<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Password Requirements Configuration
    |--------------------------------------------------------------------------
    |
    | Configure minimum password requirements for user registration and password updates.
    | These settings can be managed by administrators through the settings panel.
    |
    */

    'min_length' => env('PASSWORD_MIN_LENGTH', 8),
    'require_uppercase' => env('PASSWORD_REQUIRE_UPPERCASE', true),
    'require_numbers' => env('PASSWORD_REQUIRE_NUMBERS', true),
    'require_symbols' => env('PASSWORD_REQUIRE_SYMBOLS', false),
];
