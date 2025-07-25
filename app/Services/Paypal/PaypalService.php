<?php

namespace App\Services\Paypal;

use PaypalServerSdkLib\Models\CheckoutPaymentIntent;
use PaypalServerSdkLib\Models\Builders\OrderRequestBuilder;
use PaypalServerSdkLib\Models\Builders\AmountWithBreakdownBuilder;
use PaypalServerSdkLib\Models\Builders\PurchaseUnitRequestBuilder;

class PaypalService extends PaypalClient
{
    protected $client;

    public function __construct()
    {
        // parent::__construct();
        $this->client = self::build();
    }

    public function createOrder($currency, $amount)
    {
        $requestBody = OrderRequestBuilder::init(
            CheckoutPaymentIntent::CAPTURE,
            [
                PurchaseUnitRequestBuilder::init(
                    AmountWithBreakdownBuilder::init(
                        $currency,
                        number_format($amount, 2, '.', '')
                    )->build()
                )->build()
            ]
        )->build();

        $response = $this->client->getOrdersController()->createOrder([
            'body'    => $requestBody,
            'prefer'  => 'return=representation',
        ]);

        if ($response->isSuccess()) {
            return $response->getResult(); // Includes order ID and links
        }

        throw new \Exception('Failed to create PayPal order: ' . json_encode($response->getResult()));
    }

    public function captureOrder(string $paypalOrderId)
    {
        $response = $this->client->getOrdersController()->captureOrder(['id' => $paypalOrderId]);

        if ($response->isSuccess()) {
            return $response->getResult(); // Captured payment details
        }

        throw new \Exception('Failed to capture PayPal order: ' . json_encode($response->getResult()));
    }

    public function verifyOrder(string $paypalOrderId)
    {
        $response = $this->client->getOrdersController()->getOrder(['id' => $paypalOrderId]);

        if ($response->isSuccess()) {
            return $response->getResult(); // Full order details
        }

        throw new \Exception('Failed to verify PayPal order: ' . json_encode($response->getResult()));
    }
}
