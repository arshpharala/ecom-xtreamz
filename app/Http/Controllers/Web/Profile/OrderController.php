<?php

namespace App\Http\Controllers\Web\Profile;

use App\Http\Controllers\Controller;
use App\Models\Cart\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        $user = auth()->user();

        if (!$user || $order->user_id !== $user->id) {
            abort(403);
        }

        $order->load([
            'lineItems.productVariant.attributeValues.attribute',
            'lineItems.productVariant.product.translation',
            'currency',
            'billingAddress',
            'returnRequests.items.orderLineItem.productVariant.product.translation',
            'returnRequests.attachments',
            'couponUsages.coupon',
        ]);

        // Get active return reasons for the new return form inside order detail
        $reasons = \App\Models\Sales\ReturnReason::active()->get();

        return view('theme.xtremez.customers.orders.show', compact('order', 'reasons'));
    }
}
