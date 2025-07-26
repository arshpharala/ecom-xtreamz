<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaypalController extends Controller
{
    function success()
    {
        // Logic to handle successful payment
        return response()->json(['message' => 'Payment successful']);
    }

    function cancel()
    {
        return response()->json(['message' => 'Payment canceled']);
    }
}
