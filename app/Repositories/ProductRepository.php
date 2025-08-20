<?php

namespace App\Repositories;

use App\Models\Catalog\Product;
use App\Models\Catalog\ProductVariant;
use App\Services\CartService;
use Illuminate\Support\Facades\Request;

class ProductRepository
{
    public function getFiltered($perPage = 12)
    {
        $filters = Request::only([
            'is_featured',
            'is_new',
            'show_in_slider',
            'category_id',
            'brand_id',
            'price_min',
            'price_max',
            'offer',
            'tags',
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
            ->with(['offers' => function ($query) {
                $query->active();
            }])
            ->withSelection();

        // Handle pagination
        if (Request::has('page')) {
            return $query->paginate($perPage)->through(function ($productVariant) {
                return $this->transform($productVariant);
            });
        }

        return $query->limit($perPage)->get()->map(function ($productVariant) {
            return $this->transform($productVariant);
        });
    }

    public function transform($productVariant)
    {
        $productVariant->link       = route('products.show', ['slug' => $productVariant->slug, 'variant' => $productVariant->id]);
        $productVariant->image      = $productVariant->file_path ? asset('storage/' . $productVariant->file_path) : null;
        $productVariant->currency   = active_currency();
        $productVariant->price_with_currency   = price_format(active_currency(), $productVariant->price);
        $productVariant->cart_item  = (new CartService())->getItem($productVariant->id);
        $productVariant->is_in_cart = $productVariant->cart_item ? true : false;

        $productVariant->offer_data = $this->transformOffer($productVariant);

        return $productVariant;
    }

    protected function transformOffer($variant)
    {
        $offer = $variant->activeOffer();

        if (!$offer) {
            return [
                'has_offer'         => false,
                'discounted_price'  => null,
                'label'             => null,
                'title'             => null,
            ];
        }

        $discountedPrice = null;
        if ($offer->discount_type === 'percent') {
            $discountedPrice    = round($variant->price * (1 - $offer->discount_value / 100), 2);
            $label              = "{$offer->discount_value}% OFF";
        } elseif ($offer->discount_type === 'fixed') {
            $discountedPrice    = max(0, $variant->price - $offer->discount_value);
            $label              = "{$offer->discount_value} " . active_currency() . " OFF";
        }

        return [
            'has_offer'         => true,
            'discounted_price'  => $discountedPrice,
            'discounted_price_with_currency'  => price_format(active_currency(), $discountedPrice),
            'label'             => $label,
            'title'             => $offer->translation->title ?? '',
        ];
    }



    public function getGiftProducts($categorySlug = 'gift-sets', $limit = 3)
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
                    'link' => route('products.show', ['slug' => $variant->slug, 'varient' => $variant->id])
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

    public function findVariantOrFirst(Product $productVariant, ?string $variantId): ?ProductVariant
    {
        if ($variantId) {
            return $productVariant->variants->where('id', $variantId)->first();
        }

        return $productVariant->variants->first();
    }
}
