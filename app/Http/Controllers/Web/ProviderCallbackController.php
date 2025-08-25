<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderCallbackController extends Controller
{
    public function __invoke($provider)
    {
        if (!in_array($provider, ['github', 'facebook', 'google', 'twitter-oauth-2'])) {
            abort(404);
        }

        $user = Socialite::driver($provider)
            ->stateless()
            ->user();

        if (!$user || !$user->getId()) {
            return redirect()->route('login')->with('error', 'Failed to login with ' . ucfirst($provider) . '. Please try again.');
        }

        $userData = User::firstOrNew(['email' => $user->getEmail()]);

        if (!$userData->id) {
            $userData->name = $user->getName() ?? $user->getNickname() ?? 'Guest';
            $userData->password = bcrypt(Str::uuid());
        }

        if (!$userData->name) {
            $userData->name = $user->getName() ?? $user->getNickname() ?? 'Guest';
        }

        $userData->provider_id = $user->getId();
        $userData->provider_name = $provider;
        $userData->is_active = 1;
        if (!$userData->email_verified_at) {
            $userData->email_verified_at = now();
        }

        $userData->save();

        Auth::login($userData, true);

        return redirect()->route('customers.profile');
    }
}
