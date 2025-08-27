<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('customers.profile');
        }
        return view('theme.xtremez.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Successfully Logged In',
            'redirect' => route('customers.profile')
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'is_active'    => 1,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Successfully Logged In',
            'redirect' => route('customers.profile')
        ]);
    }

    function forgotPassword()
    {
        return view('theme.xtremez.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ]);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent successfully.',
                'redirect' => route('login')
            ]);
        } else {
            return response()->json([
                'success' => false,
                "message" => __($status),
                'errors' => [
                    'email' => [__($status)],
                ]
            ], 422);
        }
    }

    public function resetPasswordForm($token)
    {
        return view('theme.xtremez.auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $resetUser = null;

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();

                $user->setRememberToken(Str::random(60));

                $resetUser = $user;
            }
        );

        if ($status === Password::PASSWORD_RESET) {

            Auth::login($resetUser);

            return response()->json([
                'success' => true,
                'message' => __($status),
                'redirect' => route('customers.profile')
            ]);
        } else {
            return response()->json([
                'success' => false,
                "message" => __($status),
                'errors' => [
                    'email' => [__($status)],
                ]
            ], 422);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
