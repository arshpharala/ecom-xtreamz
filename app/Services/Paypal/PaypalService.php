<?php

namespace App\Services\Paypal;

class PaypalService extends PaypalClient
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createOrder(array $data = []): array
    {
        $payload = array_merge([
            'intent' => 'CAPTURE',
            'purchase_units' => [],
            'application_context' => [
                'brand_name'          => config('app.name'),
                'shipping_preference' => 'NO_SHIPPING',
                'user_action'         => 'PAY_NOW',
                'return_url'          => route('api.paypal.success'),
                'cancel_url'          => route('api.paypal.cancel'),
            ],
        ], $data);

        return $this->post('/v2/checkout/orders', $payload);
    }

    public function captureOrder(string $orderId): array
    {
        return $this->post("/v2/checkout/orders/{$orderId}/capture");
    }

    public function getOrderDetails(string $orderId): array
    {
        return $this->get("/v2/checkout/orders/{$orderId}");
    }
}
