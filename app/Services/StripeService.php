<?php

namespace App\Services;

use Stripe\Stripe;
use App\Models\User;
use Stripe\Customer;
use App\Models\Address;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use App\Models\CMS\PaymentGateway;

class StripeService
{
    protected $gateway;

    public function __construct()
    {
        $this->gateway = PaymentGateway::where('gateway', 'stripe')
            ->where('is_active', true)
            ->first();

        if (!$this->gateway || !$this->gateway->secret) {
            throw new \Exception("Stripe gateway is not configured.");
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
                'city'        => $address->city->name,
                'state'       => $address->province->name,
                'postal_code' => $address->postal_code ?? '00000',
                'country'     => $address->country->code,
            ],
            'name'    => $address->name,
            'phone'   => $address->phone ?? null,
        ]);
    }

    public function createIntentForNewCard($user, $amount, $metadata = [])
    {
        return PaymentIntent::create([
            'amount' => (int)($amount * 100),
            'currency' => active_currency(),
            'customer' => $user->stripe_id ?? NULL,
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => $metadata,
        ]);
    }

    public function chargeSavedCard(User $user, string $paymentMethodId, float $amount, array $meta = [])
    {

        try {
            return PaymentIntent::create([
                'amount' => (int) ($amount * 100),
                'currency' => active_currency(),
                'customer' => $user->stripe_id,
                'payment_method' => $paymentMethodId,
                'off_session' => true,
                'confirm' => true,
                'metadata' => $meta,
            ]);
        } catch (\Stripe\Exception\CardException $e) {

            return [  // This card requires authentication
                'requires_action' => true,
                'payment_intent_id' => $e->getError()->payment_intent->id ?? null,
                'client_secret' => $e->getError()->payment_intent->client_secret ?? null,
            ];
        }
    }

    public function saveCardForUser(User $user, string $paymentMethodId): void
    {
        $this->ensureStripeCustomer($user); // Ensures stripe_id

        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
        $paymentMethod->attach(['customer' => $user->stripe_id]);

        // Set as default
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
