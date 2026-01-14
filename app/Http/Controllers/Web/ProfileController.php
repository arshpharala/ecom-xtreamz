<?php

namespace App\Http\Controllers\Web;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentMethod;
use App\Models\CMS\Province;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use App\Services\StripeService;
use App\Models\CMS\PaymentGateway;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = auth()->user();

        return view('theme.xtremez.customers.profile');
    }

    public function loadTab(string $tab)
    {
        $data = [];
        $user = auth()->user();

        switch ($tab) {
            case 'order':
                $user = $user->load('orders.lineItems');
                break;
            case 'address':
                $user = $user->load('addresses');
                $data['provinces'] = Province::where('country_id', 1)->get();
                break;
            case 'wishlist':
                $user = $user->load('wishlist');
                break;
            case 'returns':
                $user = $user->load('returnRequests.reason', 'returnRequests.items.orderLineItem.productVariant.product', 'returnRequests.attachments', 'returnRequests.order');
                // Also get return reasons for the "New Return" form
                $data['reasons'] = \App\Models\Sales\ReturnReason::active()->get();
                // Get eligible orders (paid and within return period)
                $data['eligibleOrders'] = $user->orders()->paid()->get()->filter(function($order) {
                    return $order->canBeReturned();
                });
                break;
            case 'payment':
                // $stripe = new StripeService(); // sets key from DB automatically
                $stripe       = PaymentGateway::active()->where('gateway', 'Stripe')->first();
                config(['cashier.secret' => $stripe->secret]);
                $data['stripe']       = $stripe;
                break;

            default:

                break;
        }


        $data['user'] = $user;

        $view = "theme.xtremez.components.profile.$tab";
        if (!View::exists($view)) {

            $response['view'] = '<div class="p-3 text-danger">Invalid tab</div>';
            return response()->json([
                'success' => false,
                'message' => "View not found",
                'data' => $response
            ], 404);
        }

        $response['view'] = view($view, $data)->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'mobile' => ['required', 'regex:/^\+?\d{1,4}?[-\s]?\d{6,14}$/'],
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
        ]);

        $user = auth()->user();
        $user->update($request->only('name', 'email'));

        $user->detail()->updateOrCreate([], [
            'mobile' => $request->mobile,
            'dob' => $request->dob,
            'gender' => $request->gender,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile Updated.',
            'redirect' => route('customers.profile')
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }
}
