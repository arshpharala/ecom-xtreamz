<?php

namespace App\Http\Controllers\Web;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use App\Models\Cart\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Cart\UserCard;
use App\Services\CartService;
use App\Models\Cart\OrderLineItem;
use App\Models\Cart\BillingAddress;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function showGuestForm()
    {
        return view('theme.xtremez.checkout-guest');
    }

    public function processGuestOrder(Request $request)
    {
        $data = $request->validate([
            'email'     => 'nullable|email',
            'name'      => 'required|string',
            'phone'     => 'required|string',
            'province'  => 'required|string',
            'city'      => 'required|string',
            'area'      => 'required|string',
            'address'   => 'required|string',
            'landmark'  => 'nullable|string',
            'payment_method' => 'required|in:card,paypal',
            'card_name' => 'nullable|string',
        ]);

        // Save billing address
        $billingAddress = BillingAddress::create([
            'user_id' => auth()->id(),
            ...$data,
        ]);

        $cartService = new CartService();
        $total = $cartService->getTotal();
        $cartItems = $cartService->getItems();

        // Create order
        $order = Order::create([
            'order_number'         => Str::uuid(),
            'billing_address_id'   => $billingAddress->id,
            'email'                => $data['email'] ?? null,
            'payment_method'       => $data['payment_method'],
            'payment_status'       => 'pending',
            'total'                => $total,
        ]);

        foreach ($cartItems as $variantId => $item) {
            $order->lineItems()->create([
                'product_variant_id' => $variantId,
                'quantity'           => $item['qty'],
                'price'              => $item['price'],
                'subtotal'           => $item['subtotal'],
            ]);
        }

        if ($data['payment_method'] === 'card') {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $intent = PaymentIntent::create([
                'amount'   => (int) ($total * 100),
                'currency' => active_currency(), // e.g., 'aed'
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
                'metadata' => [
                    'order_id' => $order->id,
                    'email'    => $order->email ?? 'guest',
                ],
            ]);

            // Store intent ID in order (payment still pending)
            $order->update([
                'stripe_payment_intent_id' => $intent->id,
                'payment_status'           => 'pending',
            ]);

            return response()->json([
                'clientSecret' => $intent->client_secret,
                'order_id'     => $order->id,
            ]);
        }

        // If PayPal: mark pending and return success
        $cartService->clear();

        return response()->json([
            'status'   => 'paypal-selected',
            'order_id' => $order->id,
        ]);
    }

    public function thankYou(Order $order)
    {
        return view('theme.xtremez.order-confirmation', compact('order'));
    }
}
