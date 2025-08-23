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
        if (!in_array($provider, ['github', 'facebook', 'google'])) {
            abort(404);
        }

        $user = Socialite::driver($provider)
            ->user();

        if (!$user || !$user->getId()) {
            return redirect()->route('login')->with('error', 'Failed to login with ' . ucfirst($provider) . '. Please try again.');
        }

        $userData = User::firstOrNew(['email' => $user->getEmail()]);

        if ($userData->id) {
            $userData->update([
                'provider_id' => $user->getId(),
                'provider_name' => $provider,
                'active' => 1,
                'email_verified_at' => now(),
            ]);
        } else {
            $userData->update([
                'name' => $user->getName() ?? $user->getNickname() ?? 'Guest',
                'password' => bcrypt(Str::uuid()),
                'provider_id' => $user->getId(),
                'provider_name' => $provider,
                'active' => 1,
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($userData, true);

        return redirect()->route('customers.profile');
    }
}
