<?php

namespace App\Services\Mashreq;

use App\Models\CMS\PaymentGateway;
use Illuminate\Support\Facades\Http;

class MashreqService
{
    protected PaymentGateway $gateway;

    protected string $baseUrl;
    protected string $merchantId;
    protected string $username;
    protected string $password;
    protected string $currency;
    protected string $environment;

    protected int $version = 62; // MPGS stable version

    public function __construct()
    {
        $this->gateway = PaymentGateway::where('gateway', 'mashreq')
            ->where('is_active', 1)
            ->firstOrFail();

        $this->environment = $this->gateway->additional['environment'] ?? 'test';
        $this->currency    = $this->gateway->additional['currency'] ?? 'AED';
        $this->merchantId  = $this->gateway->additional['merchant_id'] ?? 'MOCK';

        $this->username    = $this->gateway->key;
        $this->password    = $this->gateway->secret;

        $this->baseUrl = match ($this->environment) {
            'live' => 'https://gateway.mastercard.com',
            'test' => 'https://test-gateway.mastercard.com',
            default => 'mock', // mock mode has no real URL
        };
    }

    /**
     * Create MPGS Checkout Session
     */
    public function createSession($order): array
    {
        /**
         * MOCK MODE
         */
        if ($this->environment === 'mock') {
            return [
                'session' => [
                    'id' => 'MOCK_SESSION_' . $order->order_number,
                ],
            ];
        }

        /**
         * TEST / LIVE
         */
        $url = "{$this->baseUrl}/api/rest/version/{$this->version}/merchant/{$this->merchantId}/session";

        return Http::withBasicAuth($this->username, $this->password)
            ->post($url, [
                'order' => [
                    'id'       => $order->order_number,
                    'amount'   => round($order->total, 2),
                    'currency' => $this->currency,
                ],
            ])
            ->json();
    }

    /**
     * Verify payment status (authoritative)
     */
    public function verifyOrder($order): array
    {
        /**
         * MOCK MODE
         */
        if ($this->environment === 'mock') {
            return [
                'status'         => 'PAID',
                'transaction_id' => 'MOCK_TXN_' . $order->order_number,
            ];
        }

        /**
         * TEST / LIVE
         */
        $url = "{$this->baseUrl}/api/rest/version/{$this->version}/merchant/{$this->merchantId}/order/{$order->order_number}";

        $response = Http::withBasicAuth($this->username, $this->password)
            ->get($url)
            ->json();

        $transaction = $response['transaction'][0]['transaction'] ?? null;
        $orderStatus = $response['status'] ?? null;

        if (
            $orderStatus === 'CAPTURED' ||
            ($transaction['type'] ?? null) === 'PAYMENT'
        ) {
            return [
                'status'         => 'PAID',
                'transaction_id' => $transaction['id'] ?? null,
                'raw'            => $response,
            ];
        }

        return [
            'status' => 'FAILED',
            'raw'    => $response,
        ];
    }
}
