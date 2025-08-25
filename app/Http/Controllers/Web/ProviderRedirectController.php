<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class ProviderRedirectController extends Controller
{
    public function __invoke($provider)
    {
        if (!in_array($provider, ['github', 'facebook', 'google', 'twitter-oauth-2'])) {
            abort(404);
        }

        return Socialite::driver($provider)
        ->redirect();
    }
}
