<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\PriceService;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductVariant;
use App\Repositories\ProductVariantRepository;

class CartController extends Controller
{
    protected $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    public function index()
    {
        $items = $this->cart->getItems();

        $variantIds = array_keys($items);

        $repository = new ProductVariantRepository();

        $variants = ProductVariant::withJoins()
            ->withSelection()
            ->whereIn('product_variants.id', $variantIds)
            ->groupBy('product_variants.id')
            ->get()
            ->map(function ($variant) use ($items, $repository) {
                $transform          = $repository->transform($variant);
                $transform->qty     = $items[$variant->variant_id]['qty'] ?? 1;
                return $transform;
            });

        $cart               = $this->cart->get();
        $data['cart']       = $cart;
        $data['variants']   = $variants;

        return view('theme.xtremez.cart', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|uuid',
            'qty'        => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::with('offers')->findOrFail($request->variant_id);
        $qty = $request->qty;

        // Check stock availability
        if (!$variant->stock || $variant->stock < $qty) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available.'
            ], 400);
        }

        $pricing = PriceService::calculateDiscountedPrice($variant);

        $this->cart->add(
            $variant->id,
            $qty,
            $pricing['final_price'],
            [
                'original_price'  => $pricing['original_price'],
                'discount_amount' => $pricing['discount_amount'],
                'offer_id'        => $pricing['offer_id'],
            ]
        );

        $variant->cart_item = $this->cart->getItem($variant->id);

        return response()->json([
            'success' => true,
            'variant' => $variant,
            'cart'    => $this->cart->get()
        ]);
    }

    public function update(Request $request, $variantId)
    {
        $request->validate([
            'variant_id' => 'required|uuid',
            'qty'        => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::with('offers')->findOrFail($variantId);
        $qty = $request->qty;

        if ($qty < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Quantity must be at least 1.'
            ], 400);
        }

        if (!$variant->stock || $variant->stock < $qty) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available.'
            ], 400);
        }

        $pricing = PriceService::calculateDiscountedPrice($variant);

        if (!$this->cart->getItem($variant->id)) {
            $this->cart->add(
                $variant->id,
                $qty,
                $pricing['final_price'],
                [
                    'original_price'  => $pricing['original_price'],
                    'discount_amount' => $pricing['discount_amount'],
                    'offer_id'        => $pricing['offer_id'],
                ]
            );
        } else {
            // Check stock availability before updating
            if (!$variant->stock || $variant->stock < $qty) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock available.'
                ], 400);
            }

            $this->cart->update($variant->id, $qty);
        }

        $variant->cart_item = $this->cart->getItem($variant->id);

        $message = $this->cart->refresh();

        return response()->json([
            'success' => true,
            'variant' => $variant,
            'cart'    => $this->cart->get(),
            'message' => $message, // <- include if not null
        ]);
    }

    public function destroy(string $variantId)
    {
        $this->cart->remove($variantId);

        $message = $this->cart->refresh();

        return response()->json([
            'success' => true,
            'cart'    => $this->cart->get(),
            'message' => $message,
        ]);
    }

    public function clearSelected(Request $request)
    {
        $request->validate([
            'variant_ids' => 'required|array|min:1',
            'variant_ids.*' => 'uuid',
        ]);

        $variantIds = $request->variant_ids;

        foreach ($variantIds as $variantId) {
            $this->cart->remove($variantId);
        }

        $message = $this->cart->refresh();

        return response()->json([
            'success' => true,
            'cart'    => $this->cart->get(),
            'message' => $message ?: 'Selected items removed successfully.',
        ]);
    }
}
