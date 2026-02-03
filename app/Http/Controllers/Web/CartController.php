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
            ->with(['attributeValues.attribute'])
            ->whereIn('product_variants.id', $variantIds)
            ->groupBy('product_variants.id')
            ->get()
            ->map(function ($variant) use ($items, $repository) {
                $transform          = $repository->transform($variant);
                $transform->qty     = $items[$variant->variant_id]['qty'] ?? 1;

                // Add variant attributes for display
                $transform->variant_attributes = $variant->attributeValues->map(function ($attrValue) {
                    return [
                        'attribute_name' => $attrValue->attribute->name,
                        'value' => $attrValue->value
                    ];
                });

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
            'variant_id' => 'nullable|uuid',
            'product_variant_id' => 'nullable|uuid',
            'qty'        => 'required|integer|min:1',
            'customization_enabled' => 'nullable|boolean',
            'customization_text' => 'nullable|string|max:1000',
            'customization_images' => 'nullable|array|max:5',
            'customization_images.*' => 'image|max:5120',
        ]);

        $variantId = $request->variant_id ?: $request->product_variant_id;

        $variant = ProductVariant::with('offers')->findOrFail($variantId);
        $qty = $request->qty;

        // Check stock availability
        if (!setting('allow_negative_purchase', false) && (!$variant->stock || $variant->stock < $qty)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available.'
            ], 400);
        }

        $pricing = PriceService::calculateDiscountedPrice($variant);

        $customizationText = trim((string) $request->input('customization_text', ''));
        $customizationImages = [];

        if ($request->hasFile('customization_images')) {
            foreach ((array) $request->file('customization_images') as $file) {
                if ($file && $file->isValid()) {
                    $customizationImages[] = $file->store('cart-customizations', 'public');
                }
            }
        }

        $options = [
            'original_price'  => $pricing['original_price'],
            'discount_amount' => $pricing['discount_amount'],
            'offer_id'        => $pricing['offer_id'],
        ];

        if ($request->boolean('customization_enabled') || $customizationText !== '' || !empty($customizationImages)) {
            $options['customization'] = [
                'text' => $customizationText,
                'images' => $customizationImages,
            ];
        }

        $this->cart->add($variant->id, $qty, $pricing['final_price'], $options);

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
            if (!setting('allow_negative_purchase', false) && (!$variant->stock || $variant->stock < $qty)) {
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
