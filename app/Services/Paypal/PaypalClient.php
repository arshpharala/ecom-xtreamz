<?php

namespace App\Services\Paypal;

use GuzzleHttp\Client as GuzzleClient;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\Models\OAuthToken;
use PaypalServerSdkLib\Models\Builders\OAuthTokenBuilder;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;

class PaypalClient
{
    protected static ?OAuthToken $token = null;

    public static function build()
    {
        $clientId = env('PAYPAL_CLIENT_ID');
        $secret   = env('PAYPAL_SECRET');
        $mode     = env('PAYPAL_MODE', 'sandbox');

        // Set environment
        $env = $mode === 'production'
            ? Environment::PRODUCTION
            : Environment::SANDBOX;

        // Fetch token if not cached or expired
        if (!self::$token || self::isTokenExpired()) {
            self::$token = self::generateAccessToken($clientId, $secret, $env);
        }

        // Build SDK client with manual token injection
        return PaypalServerSdkClientBuilder::init()
            ->oAuthToken(self::$token)
            ->environment($env)
            ->build();
    }

    protected static function generateAccessToken(string $clientId, string $secret, string $env): OAuthToken
    {
        $baseUrl = $env === Environment::PRODUCTION
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $http = new GuzzleClient([
            'verify' => false
        ]);
        $response = $http->post("{$baseUrl}/v1/oauth2/token", [
            'auth' => [$clientId, $secret],

            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return OAuthTokenBuilder::init($data['access_token'], $data['token_type'])
            ->expiresIn($data['expires_in'])
            ->scope($data['scope'] ?? null)
            ->build();
    }

    protected static function isTokenExpired(): bool
    {
        return true; // Always fetch a new token for simplicity, or implement your own logic
        $expiry = self::$token?->getExpiresIn() ?? 0;
        $fetchedAt = session('paypal_token_fetched_at', now()->subHour());

        return now()->diffInSeconds($fetchedAt) >= ($expiry - 60); // 60s buffer
    }
}
