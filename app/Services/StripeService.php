<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use App\Models\User;
use App\Models\Address;
use App\Models\CMS\PaymentGateway;
use Illuminate\Support\Facades\Cache;

class StripeService
{
    protected $gateway;

    public function __construct()
    {
        // Cache the gateway for 5 minutes to avoid repeated DB hits
        $this->gateway = Cache::remember('active_stripe_gateway', 300, function () {
            return PaymentGateway::where('gateway', 'stripe')
                ->where('is_active', true)
                ->first();
        });

        if (!$this->gateway || empty($this->gateway->secret)) {
            throw new \Exception('Stripe gateway is not configured properly.');
        }

        Stripe::setApiKey($this->gateway->secret);
    }

    public function getGateway(): ?PaymentGateway
    {
        return $this->gateway;
    }

    public function ensureStripeCustomer(User $user): void
    {
        if (!$user->stripe_id) {
            $customer = Customer::create([
                'email' => $user->email,
                'name'  => $user->name,
            ]);

            $user->update(['stripe_id' => $customer->id]);
        }
    }

    public function syncBillingAddress(User $user, Address $address): void
    {
        if (!$user->stripe_id) return;

        Customer::update($user->stripe_id, [
            'address' => [
                'line1'       => $address->address,
                'city'        => optional($address->city)->name,
                'state'       => optional($address->province)->name,
                'postal_code' => $address->postal_code ?? '00000',
                'country'     => optional($address->country)->code ?? 'AE',
            ],
            'name'  => $address->name,
            'phone' => $address->phone ?? null,
        ]);
    }

    public function createIntentForNewCard(User $user, float $amount, array $metadata = [])
    {
        $this->ensureStripeCustomer($user);

        return PaymentIntent::create([
            'amount' => (int) ($amount * 100),
            'currency' => strtolower(active_currency() ?? 'aed'),
            'customer' => $user->stripe_id,
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => $metadata,
        ]);
    }

    public function chargeSavedCard(User $user, string $paymentMethodId, float $amount, array $meta = [])
    {
        try {
            $this->ensureStripeCustomer($user);

            return PaymentIntent::create([
                'amount' => (int) ($amount * 100),
                'currency' => strtolower(active_currency() ?? 'aed'),
                'customer' => $user->stripe_id,
                'payment_method' => $paymentMethodId,
                'off_session' => true,
                'confirm' => true,
                'metadata' => $meta,
            ]);
        } catch (\Stripe\Exception\CardException $e) {
            return [
                'requires_action' => true,
                'payment_intent_id' => $e->getError()->payment_intent->id ?? null,
                'client_secret' => $e->getError()->payment_intent->client_secret ?? null,
            ];
        }
    }

    public function saveCardForUser(User $user, string $paymentMethodId): void
    {
        $this->ensureStripeCustomer($user);

        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
        $paymentMethod->attach(['customer' => $user->stripe_id]);

        // Make it default
        Customer::update($user->stripe_id, [
            'invoice_settings' => [
                'default_payment_method' => $paymentMethodId,
            ]
        ]);
    }

    public function deleteCardForUser(User $user, string $paymentMethodId): void
    {
        PaymentMethod::retrieve($paymentMethodId)->detach();
    }
}
