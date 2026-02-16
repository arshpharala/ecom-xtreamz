<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Cart\CouponUsage;
use App\Models\Cart\Order;
use App\Models\CMS\Currency;
use App\Models\CMS\PaymentGateway;
use App\Models\CMS\Province;
use App\Models\User;
use App\Notifications\OrderSuccess;
use App\Repositories\AddressRepository;
use App\Repositories\UserRepository;
use App\Services\CartService;
use App\Services\Mashreq\MashreqService;
use App\Services\Paypal\PaypalService;
use App\Services\StripeService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected CartService $cart;

    protected AddressRepository $addressRepository;

    protected UserRepository $userRepository;

    protected \App\Services\Touras\TourasService $tourasService;

    public function __construct(
        \App\Services\Touras\TourasService $tourasService
    ) {
        $this->cart = new CartService;
        $this->addressRepository = new AddressRepository(app());
        $this->userRepository = new UserRepository(app());
        $this->tourasService = $tourasService;
    }

    /* ======================================================
     | Checkout Page
     ====================================================== */
    public function checkout(Request $request)
    {
        abort_if($this->cart->getItemCount() === 0, 404);

        $data['provinces'] = Province::where('country_id', 1)->get();
        $data['gateways'] = PaymentGateway::active()->get();

        if (Auth::check()) {
            $user = $request->user();
            $data['user'] = $user;
            $data['addresses'] = $user->addresses()->latest()->get();
            $data['cards'] = $user->cards()->latest()->get();
        }

        return view('theme.xtremez.checkout', $data);
    }

    /* ======================================================
     | Process Order (ENTRY POINT)
     ====================================================== */
    public function processOrder(StoreOrderRequest $request)
    {
        abort_if($this->cart->getItemCount() === 0, 'Cart is empty.');

        DB::beginTransaction();

        try {
            $user = Auth::check() ? $request->user() : $this->createUser($request);
            $address = $this->getOrCreateAddress($request, $user);
            $order = $this->createOrder($user, $address->id, $request->payment_method);

            $this->storeLineItems($order);

            // Handle Client-Side Uploads
            $this->handleCustomizationUploads($request, $order);

            $response = match ($request->payment_method) {
                'stripe' => $this->handleStripePayment($request, $order, $user),
                'paypal' => $this->handlePaypalPayment($request, $order, $user),
                'mashreq' => $this->handleMashreqPayment($order),
                'touras' => $this->handleTourasPayment($request, $order),
                default => abort(400, 'Invalid payment method'),
            };

            DB::commit();

            return $response;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /* ======================================================
     | USER & ADDRESS
     ====================================================== */
    protected function createUser(Request $request): User
    {
        $existing = User::where('email', $request->email)->first();

        if ($existing) {
            if (! $existing->is_guest) {
                abort(403, 'An account with this email already exists. Please log in.');
            }

            return $existing;
        }

        return $this->userRepository->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt(Str::uuid()),
            'is_active' => false,
            'is_guest' => true,
        ]);
    }

    protected function getOrCreateAddress(Request $request, User $user)
    {
        if ($request->filled('saved_address_id')) {
            return $user->addresses()->findOrFail($request->saved_address_id);
        }

        return $this->addressRepository->create([
            'user_id' => $user->id,
            'email' => $request->email ?? $user->email,
            'name' => $request->name,
            'phone' => $request->phone,
            'country_id' => active_country()->id,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'area_id' => $request->area_id,
            'address' => $request->address,
            'landmark' => $request->landmark,
        ]);
    }

    /* ======================================================
     | ORDER CREATION
     ====================================================== */
    protected function createOrder(User $user, int $addressId, string $paymentMethod): Order
    {
        return Order::create([
            'order_number' => Str::uuid(),
            'user_id' => $user->id,
            'billing_address_id' => $addressId,
            'email' => $user->email,
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'status' => 'draft',
            'currency_id' => Currency::where('code', active_currency())->value('id'),
            'sub_total' => $this->cart->getSubTotal(),
            'tax' => $this->cart->getTax(),
            'total' => $this->cart->getTotal(),
        ]);
    }

    protected function storeLineItems(Order $order): void
    {
        foreach ($this->cart->getItems() as $variantId => $item) {
            $order->lineItems()->create([
                'product_variant_id' => $variantId,
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
                'options' => $item['options'] ?? [],
            ]);
        }
    }

    /* ======================================================
     | STRIPE (INITIATE)
     ====================================================== */
    protected function handleStripePayment(Request $request, Order $order, User $user)
    {
        $stripe = new StripeService;

        $stripe->ensureStripeCustomer($user);
        $stripe->syncBillingAddress($user, $order->address);

        if ($request->filled('saved_card_id')) {

            $card = $user->cards()->findOrFail($request->saved_card_id);
            $intent = $stripe->chargeSavedCard(
                $user,
                $card->card_token,
                $order->total,
                ['order_id' => $order->id]
            );

            if (! empty($intent['requires_action'])) {
                return response()->json([
                    'requires_action' => true,
                    'clientSecret' => $intent['client_secret'],
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);
            }

            if ($intent->status !== 'succeeded') {
                return back()->withErrors(['stripe' => 'Card payment failed']);
            }

            $order->update([
                'payment_status' => 'paid',
                'status' => 'placed',
                'external_reference' => $intent->id,
            ]);

            $this->applyCoupon($order);
            $this->cart->clear();

            return redirect()->route('order.summary', $order->order_number);
        }

        $intent = $stripe->createIntentForNewCard($user, $order->total, [
            'order_id' => $order->id,
        ]);

        $order->update(['external_reference' => $intent->id]);

        return response()->json([
            'clientSecret' => $intent->client_secret,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);
    }

    /* ======================================================
     | STRIPE (CONFIRM / CAPTURE)
     ====================================================== */
    public function confirmStripePayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'payment_intent_id' => 'required|string',
        ]);

        $order = Order::findOrFail($request->order_id);
        $intent = \Stripe\PaymentIntent::retrieve($request->payment_intent_id);

        if ($intent->status !== 'succeeded') {
            return response()->json(['message' => 'Payment not completed'], 422);
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'placed',
            'external_reference' => $intent->id,
        ]);

        $this->applyCoupon($order);
        $this->cart->clear();

        return response()->json([
            'redirect' => route('order.summary', $order->order_number),
        ]);
    }

    /* ======================================================
     | PAYPAL (INITIATE)
     ====================================================== */
    protected function handlePaypalPayment(Request $request, Order $order, User $user)
    {
        session(['paypal_temp_order_id' => $order->id]);

        $paypal = new PaypalService;

        $response = $paypal->createOrder([
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => $paypal->getCurrency(),
                    'value' => price_convert(
                        $order->total,
                        active_currency(),
                        $paypal->getCurrency()
                    ),
                ],
            ]],
        ]);

        return response()->json(['id' => $response['id']]);
    }

    /* ======================================================
     | PAYPAL (CAPTURE)
     ====================================================== */
    public function capturePaypalOrder(Request $request, PaypalService $paypal): JsonResponse
    {
        $request->validate(['order_id' => 'required|string']);

        $response = $paypal->captureOrder($request->order_id);

        if (! $response || $response['status'] !== 'COMPLETED') {
            return response()->json(['message' => 'Capture failed'], 422);
        }

        $order = Order::findOrFail(session('paypal_temp_order_id'));

        $order->update([
            'payment_status' => 'paid',
            'status' => 'placed',
            'external_reference' => $response['id'],
        ]);

        $this->applyCoupon($order);
        $this->cart->clear();

        return response()->json([
            'redirect' => route('order.summary', $order->order_number),
        ]);
    }

    /* ======================================================
     | MASHREQ (INITIATE)
     ====================================================== */
    protected function handleMashreqPayment(Order $order)
    {
        abort_if($order->payment_status === 'paid', 400, 'Order already paid');

        $mashreq = new MashreqService;
        $session = $mashreq->createSession($order);

        abort_if(! isset($session['session']['id']), 500, 'Mashreq session failed');

        $order->update([
            'external_reference' => $session['session']['id'],
            'payment_status' => 'pending',
        ]);

        return response()->json([
            'type' => 'mashreq',
            'session_id' => $session['session']['id'],
            'order_id' => $order->id,
        ]);
    }

    /* ======================================================
     | MASHREQ (RETURN / VERIFY)
     ====================================================== */
    public function mashreqReturn(Request $request)
    {
        $order = Order::findOrFail($request->input('order_id'));

        if ($order->payment_status === 'paid') {
            return redirect()->route('order.summary', $order->order_number);
        }

        $verify = (new MashreqService)->verifyOrder($order);

        if ($verify['status'] === 'PAID') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'placed',
                'external_reference' => $verify['transaction_id'],
            ]);

            $this->applyCoupon($order);
            $this->cart->clear();

            return redirect()->route('order.summary', $order->order_number);
        }

        $order->update(['payment_status' => 'failed']);

        return redirect()->route('checkout')
            ->withErrors(['payment' => 'Payment failed or cancelled']);
    }

    /* ======================================================
     | TOURAS (INITIATE) - JS Checkout
     ====================================================== */

    protected function handleTourasPayment(Request $request, Order $order)
    {
        $order->loadMissing(['user', 'address', 'lineItems']);

        $address = $order->address;
        $user = $order->user;

        abort_if(! $address, 422, 'Billing address missing for order.');
        abort_if(! $user, 422, 'User missing for order.');

        // Mark order pending (optional)
        $order->update([
            'payment_status' => 'pending',
            'payment_method' => 'touras',
            'external_reference' => 'touras_pending',
        ]);

        return response()->json([
            'redirect' => route('touras.pay', ['order' => $order->order_number]),
        ]);
    }

    public function tourasPay(Request $request, $orderRef)
    {
        $order = Order::where('order_number', $orderRef)->firstOrFail();
        $order->loadMissing(['user', 'address', 'lineItems']);

        $address = $order->address;
        $user = $order->user;

        abort_if(! $address, 422, 'Billing address missing for order.');
        abort_if(! $user, 422, 'User missing for order.');

        // IMPORTANT: Must match what you’ll search in return()
        $orderNo = (string) $order->reference_number;

        // Normalize amount to 2 decimals
        $amount = number_format((float) $order->total, 2, '.', '');

        $payload = [
            'order_no' => $orderNo,
            'amount' => $amount,

            'country' => 'ARE',
            'currency' => 'AED',

            'txn_type' => 'SALE',
            'channel' => 'WEB',

            // Customer
            'cust_name' => (string) ($user->name ?? 'Customer'),
            'email_id' => (string) ($user->email ?? ''),
            'mobile_no' => (string) ($address->phone ?? $user->phone ?? ''),
        ];

        // Billing details (keys must match Touras doc)
        $payload['bill_address'] = (string) ($address->address ?? '');
        $payload['bill_city'] = (string) optional($address->city)->name ?: 'City';
        $payload['bill_state'] = (string) optional($address->province)->name ?: 'State';
        $payload['bill_country'] = 'UAE';
        $payload['bill_zip'] = (string) ($address->zip ?? '00000');

        // Shipping - if same as billing for now
        $payload['ship_address'] = $payload['bill_address'];
        $payload['ship_city'] = $payload['bill_city'];
        $payload['ship_state'] = $payload['bill_state'];
        $payload['ship_country'] = $payload['bill_country'];
        $payload['ship_zip'] = $payload['bill_zip'];

        // Items (optional in doc but keep)
        $payload['item_count'] = (string) $order->lineItems->count();
        $payload['item_value'] = (string) $amount;
        $payload['item_category'] = 'Retail';

        $jsData = $this->tourasService->prepareJsPayload($payload);

        $gatewayConfig = PaymentGateway::where('gateway', 'touras')->first();
        $jsData['internalKey'] = $gatewayConfig->key;

        $data['jsData'] = $jsData;
        $data['order'] = $order;

        return view('test-touras', $data);
    }

    public function tourasReturn(Request $request)
    {
        $raw = $request->input('data');

        try {
            $response = json_decode($this->tourasService->decrypt($raw));
        } catch (DecryptException $e) {
            return response()->json([
                'redirect' => route('checkout').'?error=Invalid response from payment gateway',
            ], 422);
        }

        dd($response);
        $orderNo = $response->order_number;
        $order = Order::where('reference_number', $orderNo)->first();

        if ($order) {
            if ($response->status == 'Successful') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'placed',
                    'payment_method' => 'touras',
                    'external_reference' => $response->ag_ref,
                ]);

                $this->applyCoupon($order);
                $this->cart->clear();

                return response()->json([
                    'redirect' => route('order.summary', $order->order_number),
                ]);
            } else {
                return response()->json([
                    'redirect' => route('checkout').'?error=Payment '.$response->status,
                ]);
            }
        } else {
            return response()->json([
                'redirect' => route('checkout').'?error=Order not found: '.$orderNo,
            ], 404);
        }
    }

    /* ======================================================
     | THANK YOU
     ====================================================== */
    public function thankYou(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->where('status', '!=', 'pending')->firstOrFail();

        $data['order'] = $order->load(['lineItems.productVariant.product', 'address', 'user', 'couponUsages.coupon']);
        $this->cart->clear();

        if ($order->email && ! $order->email_sent) {
            Notification::route('mail', $order->email)
                ->notify(new OrderSuccess($order));

            $order->update(['email_sent' => true]);
        }

        return view('theme.xtremez.order-confirmation', $data);
    }

    /* ======================================================
     | COUPON HELPER (UNCHANGED LOGIC)
     ====================================================== */
    protected function applyCoupon(Order $order): void
    {
        if ($this->cart->hasCoupon()) {
            $couponData = $this->cart->getCoupon();
            CouponUsage::firstOrCreate([
                'coupon_id' => $couponData['id'],
                'order_id' => $order->id,
            ], [
                'user_id' => $order->user_id,
                'discount_amount' => $couponData['discount'],
            ]);
        }
    }

    /* ======================================================
     | LAZY UPLOAD HANDLER
     ====================================================== */
    protected function handleCustomizationUploads(Request $request, Order $order): void
    {
        // Expecting: customization_files[customization_id][] = [file, file]
        if (! $request->hasFile('customization_files')) {
            return;
        }

        $uploads = $request->file('customization_files'); // Array: customId => [files...]

        foreach ($uploads as $customizationId => $files) {
            if (empty($files)) {
                continue;
            }

            // Find the line item with this customization ID
            $lineItem = $order->lineItems->first(function ($item) use ($customizationId) {
                $options = $item->options ?? [];

                return isset($options['customization']['customization_id'])
                    && $options['customization']['customization_id'] === $customizationId;
            });

            if ($lineItem) {
                foreach ($files as $file) {
                    if ($file && $file->isValid()) {
                        $path = $file->store('cart-customizations', 'public');

                        $lineItem->attachments()->create([
                            'file_path' => $path,
                            'file_name' => $file->getClientOriginalName(),
                            'file_type' => $file->getClientMimeType(),
                        ]);
                    }
                }
            }
        }
    }
}
