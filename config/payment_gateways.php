<?php

// config/payment_gateways.php

return [
    'stripe' => [
        'label' => 'Stripe',
        'webhook' => true,
        'fields' => [
            'key' => ['label' => 'Client Key', 'encrypted' => true, 'type' => 'text', 'help' => 'Publishable key from Stripe dashboard.'],
            'secret' => ['label' => 'Client Secret', 'encrypted' => true, 'type' => 'text', 'help' => 'Secret key used to sign transactions.'],
            'display_name' => ['label' => 'Display Name', 'type' => 'text'],
        ],
    ],
    'paypal' => [
        'label' => 'PayPal',
        'webhook' => true,
        'fields' => [
            'key'           => ['label' => 'Client ID', 'encrypted' => true],
            'secret'        => ['label' => 'Client Secret', 'encrypted' => true],
            'display_name' => ['label' => 'Display Name', 'type' => 'text'],
            'currency'      => ['label' => 'Supported Currency', 'type' => 'text', 'help' => 'Paypal Support Only few currencies.'],

        ],
    ]
];
