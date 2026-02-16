<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TourasController extends Controller
{
    public function webhook(Request $request)
    {
        $logData = sprintf(
            "[%s] IP: %s\nData: %s\n%s\n",
            date('Y-m-d H:i:s'),
            $request->ip(),
            json_encode($request->all(), JSON_PRETTY_PRINT),
            str_repeat('-', 50)
        );

        \Illuminate\Support\Facades\File::append(
            storage_path('logs/touras_webhook.log'),
            $logData
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Webhook received successfully',
        ]);
    }
}
