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
use App\Services\StripeService;
use App\Models\Cart\OrderLineItem;
use App\Models\Cart\BillingAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $cart;
    protected $stripe;

    public function __construct()
    {
        $this->cart = new CartService();
        $this->stripe = new StripeService();
    }

    function checkout(Request $request)
    {
        abort_if($this->cart->getItemCount() == 0, 404);

        if (Auth::check()) {
            return $this->showAuthForm($request);
        } else {
            return $this->showGuestForm();
        }
    }

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

        $total = $this->cart->getTotal();
        $cartItems = $this->cart->getItems();

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
        $this->cart->clear();

        return response()->json([
            'status'   => 'paypal-selected',
            'order_id' => $order->id,
        ]);
    }



    public function showAuthForm(Request $request)
    {
        $user = $request->user();
        $addresses = $user->billingAddresses()->latest()->get();
        $cards = $user->cards()->latest()->get();

        return view('theme.xtremez.checkout-auth', compact('addresses', 'cards', 'user'));
    }

    public function processAuthenticatedOrder(Request $request)
    {
        $user = $request->user();

        $this->validateCheckout($request);

        $address = $this->getOrCreateBillingAddress($request, $user);
        $order = $this->createOrder($user, $address->id, $request->payment_method);
        $this->storeLineItems($order);

        return match ($request->payment_method) {
            'card' => $this->handleStripePayment($request, $order, $user, $this->cart->getTotal()),
            'paypal' => $this->handlePaypalStub($order),
            default => abort(400, 'Invalid payment method')
        };
    }

    protected function validateCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:card,paypal',
            'card_name' => 'required_without:saved_card_id|string',
            'card_token' => 'nullable|string',
            'saved_card_id' => 'nullable|exists:user_cards,id',
            'saved_address_id' => 'nullable|exists:billing_addresses,id',
            'name' => 'required_without:saved_address_id|string',
            'phone' => 'required_without:saved_address_id|string',
            'province' => 'required_without:saved_address_id|string',
            'city' => 'required_without:saved_address_id|string',
            'area' => 'required_without:saved_address_id|string',
            'address' => 'required_without:saved_address_id|string',
            'landmark' => 'nullable|string',
        ]);
    }

    protected function getOrCreateBillingAddress(Request $request, $user)
    {
        if ($request->filled('saved_address_id')) {
            return $user->billingAddresses()->findOrFail($request->saved_address_id);
        }

        return $user->billingAddresses()->create([
            'name' => $request->name,
            'phone' => $request->phone,
            'province' => $request->province,
            'city' => $request->city,
            'area' => $request->area,
            'address' => $request->address,
            'landmark' => $request->landmark,
        ]);
    }

    protected function createOrder($user, $billingAddressId, $paymentMethod)
    {
        return Order::create([
            'order_number' => Str::uuid(),
            'user_id' => $user->id,
            'billing_address_id' => $billingAddressId,
            'email' => $user->email,
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'total' => $this->cart->getTotal(),
        ]);
    }

    protected function storeLineItems(Order $order)
    {
        foreach ($this->cart->getItems() as $variantId => $item) {
            $order->lineItems()->create([
                'product_variant_id' => $variantId,
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }
    }

    public function handleStripePayment(Request $request, Order $order, $user, $total)
    {
        $stripe = new StripeService();

        $stripe->ensureStripeCustomer($user);

        $stripe->syncBillingAddress($user, [
            'name'     => $request->name ?? $user->name,
            'phone'    => $request->phone ?? '',
            'province' => $request->province ?? '',
            'city'     => $request->city ?? '',
            'address'  => $request->address ?? '',
        ]);


        if ($request->filled('saved_card_id')) {

            $card = $user->cards()->findOrFail($request->saved_card_id);
            $intent = $stripe->chargeSavedCard($user, $card->card_token, $total, ['order_id' => $order->id]);

            if (isset($intent['requires_action']) && $intent['requires_action']) {
                return response()->json([
                    'requires_action' => true,
                    'clientSecret' => $intent['client_secret'],
                    'order_id' => $order->id,
                ]);
            }

            $order->update([
                'stripe_payment_intent_id' => $intent->id,
                'payment_status' => $intent->status === 'succeeded' ? 'paid' : 'pending',
            ]);

            if ($intent->status !== 'succeeded') {
                return back()->withErrors(['stripe' => 'Card payment failed.']);
            }

            $this->cart->clear();
            return redirect()->route('order.summary', $order->id);
        }


        // If no saved card, use Stripe Elements (JS)

        $intent = $stripe->createIntentForNewCard($user, $total, ['order_id' => $order->id]);
        $order->update(['stripe_payment_intent_id' => $intent->id]);


        $this->cart->clear();

        return response()->json([
            'clientSecret' => $intent->client_secret,
            'order_id' => $order->id,
        ]);
    }


    protected function handlePaypalStub(Order $order)
    {
        $order->update([
            'payment_status' => 'paid',
        ]);

        $this->cart->clear();
        return redirect()->route('order.summary', $order->id);
    }

    public function thankYou(Order $order)
    {
        return view('theme.xtremez.order-confirmation', compact('order'));
    }
}
