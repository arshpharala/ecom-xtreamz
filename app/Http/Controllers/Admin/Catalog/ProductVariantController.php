<?php

namespace App\Http\Controllers\Admin\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use App\Models\Catalog\Attribute;
use App\Http\Controllers\Controller;

class ProductVariantController extends Controller
{
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $submittedCombinations = [];

        foreach ($request->variants as $variantInput) {
            $price = $variantInput['price'];
            $stock = $variantInput['stock'];
            $attrValueIds = array_values(array_filter($variantInput['attributes'] ?? []));

            if (count($attrValueIds) === 0) continue;

            // Check for duplicates
            $existing = $product->variants->first(function ($variant) use ($attrValueIds) {
                return $variant->attributeValues->pluck('id')->sort()->values()->toArray() === collect($attrValueIds)->sort()->values()->toArray();
            });

            if ($existing) {
                $existing->update(['price' => $price, 'stock' => $stock]);
                $submittedCombinations[] = $existing->id;
            } else {
                $variant = $product->variants()->create([
                    'price' => $price,
                    'stock' => $stock,
                ]);
                $variant->attributeValues()->sync($attrValueIds);
                $submittedCombinations[] = $variant->id;
            }
        }

        // Remove old variants
        $product->variants()->whereNotIn('id', $submittedCombinations)->delete();

        return response()->json([
            'message' => 'Variants saved successfully.',
            'redirect' => route('admin.catalog.products.edit', $product->id),
        ]);
    }
}
