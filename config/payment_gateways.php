<?php

return [

    'stripe' => [
        'label'   => 'Stripe',
        'webhook' => true,
        'fields'  => [
            'key' => [
                'label'     => 'Client Key',
                'encrypted' => true,
                'type'      => 'text',
                'help'      => 'Publishable key from Stripe dashboard.',
            ],
            'secret' => [
                'label'     => 'Client Secret',
                'encrypted' => true,
                'type'      => 'text',
                'help'      => 'Secret key used to sign transactions.',
            ],
            'display_name' => [
                'label' => 'Display Name',
                'type'  => 'text',
            ],
        ],
    ],

    'paypal' => [
        'label'   => 'PayPal',
        'webhook' => true,
        'fields'  => [
            'key' => [
                'label'     => 'Client ID',
                'encrypted' => true,
            ],
            'secret' => [
                'label'     => 'Client Secret',
                'encrypted' => true,
            ],
            'display_name' => [
                'label' => 'Display Name',
                'type'  => 'text',
            ],
            'currency' => [
                'label' => 'Supported Currency',
                'type'  => 'text',
                'help'  => 'PayPal supports limited currencies only.',
            ],
        ],
    ],

    /* =========================
     | Mashreq (MPGS)
     |=========================*/
    'mashreq' => [
        'label'   => 'Mashreq (Cards / Apple Pay)',
        'webhook' => true,

        'fields'  => [

            'key' => [
                'label'     => 'API Username',
                'encrypted' => true,
                'type'      => 'text',
                'help'      => 'Format: merchant.MERCHANT_ID (provided by Mashreq)',
            ],

            'secret' => [
                'label'     => 'API Password',
                'encrypted' => true,
                'type'      => 'password',
                'help'      => 'API password from Mashreq MPGS portal',
            ],

            'merchant_id' => [
                'label' => 'Merchant ID',
                'type'  => 'text',
                'help'  => 'Merchant ID provided by Mashreq (e.g. TESTMERCHANT)',
            ],

            'display_name' => [
                'label' => 'Display Name',
                'type'  => 'text',
                'help'  => 'Shown on checkout page (e.g. Credit / Debit Card)',
            ],

            'currency' => [
                'label' => 'Currency',
                'type'  => 'text',
                'help'  => 'Usually AED',
            ],

            'environment' => [
                'label' => 'Environment',
                'type'  => 'select',
                'options' => [
                    'test' => 'Test / Sandbox',
                    'live' => 'Live / Production',
                ],
                'help' => 'Switch between test and live MPGS environment',
            ],
        ],
    ],

];
