<?php

namespace App\Services\Jasani;

use Carbon\Carbon;
use App\Models\CMS\ApiSyncLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class JasaniClient
{
    protected string $token;
    protected string $baseUrl;
    protected string $source = 'Jasani';

    public function __construct()
    {
        $this->token   = env('JASANI_API_TOKEN');
        $this->baseUrl = env('JASANI_BASE_URL');
    }

    /* =============================
        PUBLIC API METHODS
    ============================== */

    public function fetchProducts(): array
    {
        return $this->fetchAndStore(
            "/products/all/{$this->token}",
            'jasani-products.json',
            'products'
        );
    }

    public function fetchPrices(): array
    {
        return $this->fetchAndStore(
            "/products/price/{$this->token}",
            'jasani-products-price.json',
            'prices'
        );
    }

    public function fetchStock(): array
    {
        return $this->fetchAndStore(
            "/products/stock/{$this->token}",
            'jasani-product-stock.json',
            'stock'
        );
    }

    /* =============================
        CORE HANDLER
    ============================== */

    protected function fetchAndStore(string $endpoint, string $fileName, string $type): array
    {
        $url = rtrim($this->baseUrl, '/') . $endpoint;

        try {
            $response = Http::timeout(180)
                ->retry(3, 2000)
                ->withoutVerifying()
                ->get($url);

            if (!$response->successful()) {
                $this->log($type, $url, false, 0, $response->status(), 'HTTP error');
                throw new \Exception("Failed fetching {$type}");
            }

            $data = $response->json();

            if (!is_array($data) || empty($data)) {
                $this->log($type, $url, false, 0, 200, 'Empty response');
                throw new \Exception("Empty data for {$type}");
            }

            $payload = [
                'source'          => $this->source,
                'total_records'   => count($data),
                'last_updated_at' => now()->toDateTimeString(),
                'data'            => $data,
            ];

            // Storage::disk('local')->put(
            //     $fileName,
            //     json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            // );

            $this->log($type, $url, true, count($data), 200, 'Fetched successfully');

            return $payload;
        } catch (\Throwable $e) {
            $this->log($type, $url, false, 0, null, $e->getMessage());
            throw $e;
        }
    }

    /* =============================
        LOGGER
    ============================== */

    protected function log(
        string $endpoint,
        string $url,
        bool $success,
        int $count,
        ?int $status,
        ?string $message
    ): void {
        ApiSyncLog::create([
            'source'        => $this->source,
            'endpoint'      => $endpoint,
            'url'           => $url,
            'total_records' => $count,
            'success'       => $success,
            'http_status'   => $status,
            'message'       => $message,
            'fetched_at'    => Carbon::now(),
        ]);
    }
}
