<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TourasController extends Controller
{
    public function webhook(Request $request)
    {
        $payload = $request->all();

        $logData = sprintf(
            "[%s] IP: %s\nData: %s\n%s\n",
            date('Y-m-d H:i:s'),
            $request->ip(),
            json_encode($payload, JSON_PRETTY_PRINT),
            str_repeat('-', 50)
        );

        \Illuminate\Support\Facades\File::append(
            storage_path('logs/touras_webhook.log'),
            $logData
        );

        if (! is_array($payload)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid payload format'], 400);
        }

        foreach ($payload as $item) {
            $data = $item['Data'] ?? null;
            if (! $data) {
                continue;
            }

            // Success: SaleRP
            if (! empty($data['SaleRP'])) {
                foreach ($data['SaleRP'] as $txn) {
                    $this->processTransaction($txn, 'paid');
                }
            }

            // Failure: SaleRF
            if (! empty($data['SaleRF'])) {
                foreach ($data['SaleRF'] as $txn) {
                    $this->processTransaction($txn, 'failed');
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Webhook processed successfully',
        ]);
    }

    protected function processTransaction(array $txn, string $statusType)
    {
        $orderNo = $txn['orderNo'] ?? null;
        $agRef = $txn['agRef'] ?? null;
        $status = $txn['status'] ?? null;

        if (! $orderNo) {
            return;
        }

        $order = Order::where('reference_number', $orderNo)->first();

        if (! $order) {
            Log::channel('single')->warning("Touras Webhook: Order not found for reference {$orderNo}");

            return;
        }

        if ($statusType === 'paid' && $status == '16') {
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'placed',
                    'external_reference' => $agRef,
                ]);
                Log::channel('single')->info("Touras Webhook: Order {$orderNo} marked as PAID");
            }
        } elseif ($statusType === 'failed' && $status == '17') {
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'failed',
                ]);
                Log::channel('single')->info("Touras Webhook: Order {$orderNo} marked as FAILED");
            }
        }
    }
}
