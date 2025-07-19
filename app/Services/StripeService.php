<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use App\Models\User;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
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

    public function syncBillingAddress(User $user, array $address): void
    {
        if (!$user->stripe_id) return;

        Customer::update($user->stripe_id, [
            'address' => [
                'line1'       => $address['address'],
                'city'        => $address['city'],
                'state'       => $address['province'],
                'postal_code' => '00000',
                'country'     => 'AE',
            ],
            'name'    => $address['name'],
            'phone'   => $address['phone'] ?? null,
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
}
