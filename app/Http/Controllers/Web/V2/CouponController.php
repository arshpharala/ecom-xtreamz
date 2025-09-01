<?php

namespace App\Http\Controllers\Web\V2;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\CouponService;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function apply(Request $request, CouponService $couponService, CartService $cartService)
    {
        $request->validate(['code' => 'required|string']);

        $cartItems = $cartService->getItems();
        $cartTotal = $cartService->getSubtotal();
        $variantIds = array_keys($cartItems);

        $result = $couponService->applyCoupon($request->code, $cartTotal, auth()->user(), $variantIds);

        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => $result['message']], 400);
        }

        session()->put('applied_coupon', [
            'code'     => $request->code,
            'discount' => $result['discount'],
            'id'       => $result['coupon']->id,
        ]);

        return response()->json([
            'success' => true,
            'discount' => $result['discount'],
            'final_total' => $result['final_total'],
        ]);
    }

    public function remove()
    {
        session()->forget('applied_coupon');
        return response()->json(['success' => true]);
    }
}
