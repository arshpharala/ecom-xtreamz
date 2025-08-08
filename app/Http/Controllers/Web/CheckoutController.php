<?php

namespace App\Http\Controllers\Web;

use Stripe\Stripe;
use App\Models\User;
use Stripe\PaymentIntent;
use App\Models\Cart\Order;
use Illuminate\Support\Str;
use App\Models\CMS\Currency;
use App\Models\CMS\Province;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\StripeService;
use App\Models\Cart\CouponUsage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Cart\BillingAddress;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Services\Paypal\PaypalService;
use App\Repositories\AddressRepository;
use App\Http\Requests\StoreOrderRequest;
use App\Models\CMS\PaymentGateway;

class CheckoutController extends Controller
{
    protected $cart;
    protected $stripe;
    protected $addressRepository;
    protected $userRepository;

    public function __construct()
    {
        $this->cart                 = new CartService();
        $this->stripe               = new StripeService();
        $this->addressRepository    = new AddressRepository(app());
        $this->userRepository       = new UserRepository(app());
    }

    function checkout(Request $request)
    {
        abort_if($this->cart->getItemCount() == 0, 404);

        $data['provinces']      = Province::where('country_id', 1)->get();
        $data['gateways']       = PaymentGateway::active()->get();

        if (Auth::check()) {
            $user                   = $request->user();
            $data['user']           = $user;
            $data['addresses']      = $user->addresses()->latest()->get();
            $data['cards']          = $user->cards()->latest()->get();
        }

        return view('theme.xtremez.checkout', $data);
    }

    public function processOrder(StoreOrderRequest $request)
    {
        abort_if($this->cart->getItemCount() == 0, 'Cart is empty.');
        DB::beginTransaction();

        try {
            $user    = Auth::check() ? $request->user() : $this->createUser($request);
            $address = $this->getOrCreateAddress($request, $user);
            $order   = $this->createOrder($user, $address->id, $request->payment_method);
            $this->storeLineItems($order);

            $response = match ($request->payment_method) {
                'stripe'   => $this->handleStripePayment($request, $order, $user),
                'paypal' => $this->handlePaypalPayment($request, $order, $user),
                default => abort(400, 'Invalid payment method'),
            };

            DB::commit();

            return $response;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    function createUser($request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (!$user->is_active || $user->is_guest) {
                return $user;
            }

            abort(403, 'An account with this email already exists. Please log in to continue.');
        }


        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt(Str::uuid()),
            'is_active' => 0,
            'is_guest'  => true
        ];

        return $this->userRepository->create($data);
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
            'currency_id'           => Currency::where('code', active_currency())->value('id'),
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

    public function handleStripePayment(Request $request, Order $order, $user)
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
                'external_reference'        => $intent->id,
                'payment_status'            => $intent->status === 'succeeded' ? 'paid' : 'pending',
            ]);

            if ($intent->status !== 'succeeded') {
                return back()->withErrors(['stripe' => 'Card payment failed.']);
            }

            if ($this->cart->hasCoupon()) {
                CouponUsage::create([
                    'coupon_id' => $this->cart->getCoupon()['id'],
                    'user_id'   => $user->id,
                    'order_id'  => $order->id
                ]);
            }

            $this->cart->clear();

            return redirect()->route('order.summary', $order->id);
        }


        $intent = $stripe->createIntentForNewCard($user, $order->total, ['order_id' => $order->id]);
        $order->update(['external_reference' => $intent->id]);

        $this->cart->clear();

        return response()->json([
            'clientSecret'  => $intent->client_secret,
            'order_id'      => $order->id,
            'order_number'  => $order->order_number,
        ]);
    }

    public function confirmStripePayment(Request $request)
    {
        $request->validate([
            'order_id'     => 'required|integer',
            'payment_intent_id' => 'required|string',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Optionally fetch payment intent from Stripe to double-check
        $intent = \Stripe\PaymentIntent::retrieve($request->payment_intent_id);

        if ($intent->status !== 'succeeded') {
            return response()->json(['message' => 'Payment not completed.'], 422);
        }

        $order->update([
            'payment_status' => 'paid',
            'external_reference' => $intent->id,
        ]);

        if ($this->cart->hasCoupon()) {
            CouponUsage::create([
                'coupon_id' => $this->cart->getCoupon()['id'],
                'user_id'   => $user->id,
                'order_id'  => $order->id
            ]);
        }

        $this->cart->clear();

        return response()->json([
            'redirect' => route('order.summary', $order->order_number),
        ]);
    }


    protected function handlePaypalPayment(Request $request, Order $order, $user)
    {
        session(['paypal_temp_order_id' => $order->id]);

        $paypal = new PaypalService();

        $order = $paypal->createOrder([
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $paypal->getCurrency(),
                        'value'         => price_convert($order->total, active_currency(), $paypal->getCurrency()),
                    ]
                ]
            ]
        ]);

        $orderId = $order['id'];
        $approvalLink = collect($order['links'])->firstWhere('rel', 'approve')['href'];

        if (!$approvalLink) {
            return response()->json(['message' => 'Failed to create PayPal order'], 422);
        }

        return response()->json(['id' => $orderId]);
    }



    public function capturePaypalOrder(Request $request, PaypalService $paypal): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);
        if (!$request->order_id) {
            return response()->json(['message' => 'Order ID is required'], 422);
        }

        $paypalResponse = $paypal->captureOrder($request->order_id);

        if (!$paypalResponse || $paypalResponse['status'] !== 'COMPLETED') {
            return response()->json(['message' => 'Capture failed'], 422);
        }

        $orderId = session('paypal_temp_order_id');
        $order = Order::findOrFail($orderId);

        $order->update([
            'payment_status'       => 'paid',
            'external_reference'   => $paypalResponse['id'],
        ]);

        if ($this->cart->hasCoupon()) {
            CouponUsage::create([
                'coupon_id' => $this->cart->getCoupon()['id'],
                'user_id'   => $order->user->id,
                'order_id'  => $order->id
            ]);
        }

        $this->cart->clear();

        return response()->json(['redirect' => route('order.summary', $order->order_number)]);
    }


    public function thankYou(string $orderNumber)
    {
        $data['order']  = Order::where('order_number', $orderNumber)->firstOrFail();

        $this->cart->clear();

        return view('theme.xtremez.order-confirmation', $data);
    }
}
