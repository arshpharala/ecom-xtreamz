<?php

namespace App\Http\Controllers\Web;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Cart\Order;
use Illuminate\Support\Str;
use App\Models\CMS\Province;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\StripeService;
use App\Models\Cart\BillingAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\AddressRepository;
use App\Http\Requests\StoreAuthenticatedOrderRequest;

class CheckoutController extends Controller
{
    protected $cart;
    protected $stripe;
    protected $addressRepository;

    public function __construct()
    {
        $this->cart                 = new CartService();
        $this->stripe               = new StripeService();
        $this->addressRepository    = new AddressRepository(app());
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
        $data['provinces'] = Province::where('country_id', 1)->get();
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
        $user                   = $request->user();
        $data['user']           = $user;
        $data['addresses']      = $user->addresses()->latest()->get();
        $data['cards']          = $user->cards()->latest()->get();
        $data['provinces']      = Province::where('country_id', 1)->get();

        return view('theme.xtremez.checkout-auth', $data);
    }

    public function processAuthenticatedOrder(StoreAuthenticatedOrderRequest $request)
    {
        abort_if($this->cart->getItemCount() == 0, 'Cart is empty.');

        $user       = $request->user();
        $address    = $this->getOrCreateAddress($request, $user);
        $order      = $this->createOrder($user, $address->id, $request->payment_method);

        $this->storeLineItems($order);

        return match ($request->payment_method) {
            'card' => $this->handleStripePayment($request, $order, $user, $this->cart->getTotal()),
            'paypal' => $this->handlePaypalStub($order),
            default => abort(400, 'Invalid payment method')
        };
    }

    protected function getOrCreateAddress(Request $request, $user)
    {
        if ($request->filled('saved_address_id')) {
            return $user->addresses()->findOrFail($request->saved_address_id);
        }

        $obj = [
            'user_id'       => $user->id,
            'email'         => $request->email ?? $user->email,
            'name'          => $request->name,
            'phone'         => $request->phone,
            'country_id'    => active_country()->id,
            'province_id'   => $request->province_id,
            'city_id'       => $request->city_id,
            'area_id'       => $request->area_id,
            'address'       => $request->address,
            'landmark'      => $request->landmark,
        ];

        return $this->addressRepository->create($obj);
    }

    protected function createOrder($user, $addressId, $paymentMethod)
    {
        return Order::create([
            'order_number'          => Str::uuid(),
            'user_id'               => $user->id,
            'billing_address_id'    => $addressId,
            'email'                 => $user->email,
            'payment_method'        => $paymentMethod,
            'payment_status'        => 'pending',
            'sub_total'             => $this->cart->getSubTotal(),
            'tax'                   => $this->cart->getTax(),
            'total'                 => $this->cart->getTotal(),
        ]);
    }

    protected function storeLineItems(Order $order)
    {
        foreach ($this->cart->getItems() as $variantId => $item) {
            $order->lineItems()->create([
                'product_variant_id'    => $variantId,
                'quantity'              => $item['qty'],
                'price'                 => $item['price'],
                'subtotal'              => $item['subtotal'],
            ]);
        }
    }

    public function handleStripePayment(Request $request, Order $order, $user, $total)
    {
        $stripe     = new StripeService();

        $stripe->ensureStripeCustomer($user);
        $stripe->syncBillingAddress($user, $order->address);

        if ($request->filled('saved_card_id')) {

            $card       = $user->cards()->findOrFail($request->saved_card_id);
            $intent     = $stripe->chargeSavedCard($user, $card->card_token, $total, ['order_id' => $order->id]);

            if (isset($intent['requires_action']) && $intent['requires_action']) {

                return response()->json([
                    'requires_action'       => true,
                    'clientSecret'          => $intent['client_secret'],
                    'order_id'              => $order->id,
                    'order_number'          => $order->order_number,
                ]);
            }

            $order->update([
                'stripe_payment_intent_id'  => $intent->id,
                'payment_status'            => $intent->status === 'succeeded' ? 'paid' : 'pending',
            ]);

            if ($intent->status !== 'succeeded') {
                return back()->withErrors(['stripe' => 'Card payment failed.']);
            }

            $this->cart->clear();

            return redirect()->route('order.summary', $order->id);
        }


        $intent = $stripe->createIntentForNewCard($user, $total, ['order_id' => $order->id]);
        $order->update(['stripe_payment_intent_id' => $intent->id]);

        $this->cart->clear();

        return response()->json([
            'clientSecret'  => $intent->client_secret,
            'order_id'      => $order->id,
            'order_number'  => $order->order_number,
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

    public function thankYou(string $orderNumber)
    {
        $data['order']  = Order::where('order_number', $orderNumber)->firstOrFail();

        $this->cart->clear();

        return view('theme.xtremez.order-confirmation', $data);
    }
}
