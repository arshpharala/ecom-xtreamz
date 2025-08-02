<?php

namespace App\Services\Paypal;

use Illuminate\Support\Str;
use App\Models\CMS\PaymentGateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

class PaypalClient
{
    protected $client;
    protected $clientId;
    protected $clientSecret;
    protected $baseUri;
    protected $mode;
    protected $currency;

    public function __construct()
    {
        $gateway = PaymentGateway::where('gateway', 'paypal')->active()->first();

        $this->clientId     = $gateway->key;
        $this->clientSecret = $gateway->secret;

        if (!$this->clientId || !$this->clientSecret) {
            throw new \RuntimeException('PayPal credentials not set in DB');
        }

        $this->mode = Str::lower($gateway->additional['mode'] ?? 'sandbox');
        $this->currency = Str::upper($gateway->additional['currency'] ?? 'USD');

        $this->baseUri = $gateway->additional['base_uri'] ?? (
            $this->mode === 'sandbox'
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com'
        );

        $this->client = $this->buildClient();
    }

    protected function buildClient(): GuzzleClient
    {
        return new GuzzleClient([
            'base_uri' => $this->baseUri,
            'verify'   => app()->environment('production'),
            'headers' => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
            ],
        ]);
    }

    public function get(string $endpoint, array $query = [])
    {
        return $this->request('GET', $endpoint, [
            'query' => $query,
        ]);
    }

    public function post(string $endpoint, array $body = [], array $query = []): array
    {
        $options = [];

        if (!empty($query)) {
            $options['query'] = $query;
        }

        if (!empty($body)) {
            $options['json'] = $body;
        }

        return $this->request('POST', $endpoint, $options);
    }


    public function put(string $endpoint, array $body = [], array $query = [])
    {
        return $this->request('PUT', $endpoint, [
            'query' => $query,
            'json'  => $body,
        ]);
    }

    public function patch(string $endpoint, array $body = [], array $query = [])
    {
        return $this->request('PATCH', $endpoint, [
            'query' => $query,
            'json'  => $body,
        ]);
    }

    public function delete(string $endpoint, array $body = [], array $query = [])
    {
        return $this->request('DELETE', $endpoint, [
            'query' => $query,
            'json'  => $body,
        ]);
    }

    protected function request(string $method, string $endpoint, array $options = [])
    {
        try {
            $response = $this->client->request($method, $endpoint, $options);
            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            $status = $e->getResponse()?->getStatusCode();
            $body   = $e->getResponse()?->getBody()?->getContents();
            Log::error("PayPal $method request failed", [
                'endpoint' => $endpoint,
                'status'   => $status,
                'body'     => $body,
            ]);
            throw new \RuntimeException("PayPal $method request failed: HTTP $status");
        }
    }

    protected function getAccessToken(): string
    {
        $token = Cache::get('paypal_access_token');

        if ($token && isset($token['access_token']) && !$this->isTokenExpired($token)) {
            return $token['access_token'];
        }

        return $this->requestNewToken();
    }

    protected function requestNewToken(): string
    {
        try {
            $client = new GuzzleClient([
                'base_uri' => $this->baseUri,
                'auth'     => [$this->clientId, $this->clientSecret],
                'headers'  => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept'       => 'application/json',
                ],
                'verify' => app()->environment('production'),
            ]);

            $response = $client->post('/v1/oauth2/token', [
                'form_params' => ['grant_type' => 'client_credentials'],
            ]);

            $data = json_decode($response->getBody(), true);

            if (empty($data['access_token']) || empty($data['expires_in'])) {
                throw new \RuntimeException('Invalid token response from PayPal');
            }

            $data['fetched_at'] = now();
            Cache::put('paypal_access_token', $data, now()->addSeconds($data['expires_in'] - 60));

            return $data['access_token'];
        } catch (ClientException $e) {
            $status = $e->getResponse()?->getStatusCode();
            $body   = $e->getResponse()?->getBody()?->getContents();
            Log::error('PayPal token request failed', [
                'status' => $status,
                'body'   => $body,
            ]);
            throw new \RuntimeException("PayPal token request failed: HTTP $status");
        }
    }

    protected function isTokenExpired(array $token): bool
    {
        $fetchedAt = $token['fetched_at'] ?? now()->subMinutes(70);
        $expiresIn = $token['expires_in'] ?? 0;
        return now()->diffInSeconds($fetchedAt) >= ($expiresIn - 60);
    }
}
