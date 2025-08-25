<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;

class ProviderRedirectController extends Controller
{
    public function __invoke($provider)
    {
        if (!in_array($provider, ['github', 'facebook', 'google'])) {
            abort(404);
        }

        return Socialite::driver($provider)
        ->redirect();
    }
}
