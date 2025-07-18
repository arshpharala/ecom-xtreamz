<?php

namespace App\Repositories;

use App\Models\Catalog\Product;
use App\Models\Catalog\ProductVariant;
use Illuminate\Support\Facades\Request;

class ProductRepository
{
    public function getFiltered($perPage = 12)
    {
        $locale = app()->getLocale();
        $filters = Request::only([
            'is_featured',
            'show_in_slider',
            'category_id',
            'brand_id',
            'price_min',
            'price_max',
            'search',
            'sort_by'
        ]);

        $filters['attributes'] = collect(Request::all())
            ->filter(fn($val, $key) => str_starts_with($key, 'attr_'))
            ->mapWithKeys(fn($val, $key) => [str_replace('attr_', '', $key) => $val])
            ->toArray();

        $query = ProductVariant::withJoins()
            ->withFilters($filters)
            ->applySorting($filters['sort_by'] ?? null)
            ->withSelection();

        // Handle pagination
        if (Request::has('page')) {
            return $query->paginate($perPage)->through(function ($product) {
                return $this->transformProduct($product);
            });
        }

        return $query->limit($perPage)->get()->map(function ($product) {
            return $this->transformProduct($product);
        });
    }

    public function transformProduct($product)
    {
        $product->link = route('products.show', ['slug' => $product->slug, 'variant' => $product->variant_id]);
        $product->image = $product->file_path ? asset('storage/' . $product->file_path) : null;
        $product->currency = active_currency();
        return $product;
    }


    public function getGiftProducts($categorySlug = 'gift-bags', $limit = 3)
    {
        return ProductVariant::withJoins()
            ->select(
                'product_variants.id',
                'product_variants.price',
                'products.slug',
                'product_translations.name',
                'main_attachment.file_path',
                'main_attachment.file_name'
            )
            ->orderBy('products.position')
            ->where('categories.slug', $categorySlug)
            ->limit($limit)
            ->get()
            ->map(function ($variant) {
                return (object)[
                    'name' => $variant->name,
                    'price' => $variant->price,
                    'image' => $variant->file_path ? 'storage/' . $variant->file_path : 'default.jpg',
                    'link' => route('products.show', ['slug' => $variant->slug, 'varient' => $variant->variant_id])
                ];
            });
    }

    public function findBySlugWithRelations(string $slug): ?Product
    {
        return Product::with([
            'translations',
            'category.translations',
            'brand',
            'variants.attributeValues.attribute',
            'variants.attachments',
            'variants.shipping',
        ])
            ->where('slug', $slug)
            ->first();
    }

    public function findVariantOrFirst(Product $product, ?string $variantId): ?ProductVariant
    {
        if ($variantId) {
            return $product->variants->where('id', $variantId)->first();
        }

        return $product->variants->first();
    }
}
