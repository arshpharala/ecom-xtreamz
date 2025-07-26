<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductVariant;
use App\Repositories\ProductRepository;
use Illuminate\Support\Collection;

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

        $repository = new ProductRepository();

        $variants = ProductVariant::withJoins()
            ->withSelection()
            ->whereIn('product_variants.id', $variantIds)
            ->get()
            ->map(function ($variant) use ($items, $repository) {
                $transform          = $repository->transform($variant);
                $transform->qty     = $items[$variant->variant_id]['qty'] ?? 1;
                return $transform;
            });

        $subtotal = $this->cart->getSubtotal();
        $taxes = 0;
        $total = $subtotal + $taxes;

        return view('theme.xtremez.cart', compact('variants', 'subtotal', 'taxes', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'variant_id'    => 'required|uuid',
            'qty'           => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::findOrFail($request->variant_id);

        $this->cart->add(
            $variant->id,
            $request->qty,
            $variant->price
        );

        $variant->cart_item = $this->cart->getItem($variant->id);

        return response()->json([
            'success' => true,
            'variant' => $variant,
            'cart' => $this->cart->get()
        ]);
    }

    public function update(Request $request, $variantId)
    {
        $request->validate([
            'variant_id'    => 'required|uuid',
            'qty' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::findOrFail($variantId);

        if (!$this->cart->getItem($variant->id)) {
            $this->cart->add(
                $variant->id,
                $request->qty,
                $variant->price
            );
        } else {
            $this->cart->update($variantId, $request->qty);
        }


        $variant->cart_item = $this->cart->getItem($variant->id);

        return response()->json([
            'success' => true,
            'variant' => $variant,
            'cart' => $this->cart->get()
        ]);
    }

    public function destroy(string $variantId)
    {
        $this->cart->remove($variantId);
        return response()->json([
            'success' => true,
            'cart' => $this->cart->get()
        ]);
    }
}
