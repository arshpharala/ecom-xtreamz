<?php

namespace App\Console\Commands;

use App\Models\Catalog\Attribute;
use App\Models\Catalog\AttributeValue;
use App\Models\Catalog\Brand;
/* ================= MODELS ================= */
use App\Models\Catalog\Category;
use App\Models\Catalog\Inventory;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductVariant;
use App\Models\Catalog\ProductVariantPackaging;
use App\Models\CMS\ApiSyncLog;
use App\Models\CMS\Packaging;
use App\Models\CMS\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaveJasaniData extends Command
{
    protected $signature = 'jasani:save';

    protected $description = 'Save Jasani catalog sync (products, variants, prices, stock, packaging, tags)';

    protected array $priceMap = [];
    protected array $stockMap = [];

    /** Jasani discount settings (loaded once per run) */
    protected float $jasaniDiscountPercent = 5.0;

    /** @var string[] UUID strings */
    protected array $jasaniExcludedCategoryIds = [];

    /* =====================================================
       HANDLE (ORCHESTRATION ONLY)
    ===================================================== */

    public function handle()
    {
        ini_set('max_execution_time', '300');

        // Load settings once
        $this->loadJasaniDiscountSettings();

        $products = $this->loadProducts();
        $this->loadPrices();
        $this->loadStock();

        if (empty($products)) {
            ApiSyncLog::create([
                'source' => 'Jasani',
                'endpoint' => 'catalog',
                'success' => false,
                'total_records' => 0,
                'message' => 'Product data empty – sync aborted',
                'fetched_at' => now(),
            ]);

            $this->error('No products found.');

            return Command::FAILURE;
        }

        try {
            DB::transaction(fn() => $this->syncCatalog($products));

            ApiSyncLog::create([
                'source' => 'Jasani',
                'endpoint' => 'catalog',
                'success' => true,
                'total_records' => count($products),
                'message' => 'Catalog saved successfully',
                'fetched_at' => now(),
            ]);

            $this->info('Catalog sync completed successfully.');

            return Command::SUCCESS;
        } catch (\Throwable $e) {

            ApiSyncLog::create([
                'source' => 'Jasani',
                'endpoint' => 'catalog',
                'success' => false,
                'total_records' => 0,
                'message' => $e->getMessage(),
                'fetched_at' => now(),
            ]);

            throw $e;
        }
    }

    /* =====================================================
       LOADERS (FIXED)
    ===================================================== */

    protected function loadProducts(): array
    {
        $path = Storage::disk('local')->path('jasani-products.json');

        if (! file_exists($path)) {
            return [];
        }

        $json = json_decode(file_get_contents($path), true);

        return $json['data'] ?? [];
    }

    protected function loadPrices(): void
    {
        $path = Storage::disk('local')->path('jasani-products-price.json');

        if (! file_exists($path)) {
            return;
        }

        $json = json_decode(file_get_contents($path), true);

        foreach ($json['data'] ?? [] as $row) {
            if (! empty($row['id'])) {
                $this->priceMap[(int) $row['id']] = (float) ($row['retail_price'] ?? 0);
            }
        }
    }

    protected function loadStock(): void
    {
        $path = Storage::disk('local')->path('jasani-product-stock.json');

        if (! file_exists($path)) {
            $path = Storage::disk('local')->path('private/jasani-product-stock.json');
        }

        if (! file_exists($path)) {
            return;
        }

        $json = json_decode(file_get_contents($path), true);

        foreach ($json['data'] ?? [] as $row) {
            if (! empty($row['id'])) {
                $this->stockMap[(int) $row['id']] = $row;
            }
        }
    }

    /* =====================================================
       SETTINGS (DISCOUNT)  ✅ UUID SAFE
    ===================================================== */

    protected function loadJasaniDiscountSettings(): void
    {
        // default 5%
        $percent = (float) setting('jasani_price_discount_percent', 5);

        // safety
        if ($percent < 0) $percent = 0;
        if ($percent > 90) $percent = 90;

        $this->jasaniDiscountPercent = $percent;

        // excluded categories saved as JSON array OR comma string
        $raw = setting('jasani_discount_excluded_category_ids', '[]');

        $decoded = null;

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // fallback: treat as comma list
                $decoded = array_filter(array_map('trim', explode(',', $raw)));
            }
        } elseif (is_array($raw)) {
            $decoded = $raw;
        }

        if (is_array($decoded)) {
            // ✅ UUID-safe: store as strings, never intval()
            $this->jasaniExcludedCategoryIds = array_values(
                array_unique(array_map('strval', $decoded))
            );
        } else {
            $this->jasaniExcludedCategoryIds = [];
        }
    }

    /**
     * Resolve discount for a product based on its categories.
     * Priority:
     * 1. Excluded Categories (No discount)
     * 2. Category-specific offer
     * 3. Global setting (fallback)
     *
     * @param string[] $categoryIds UUID strings
     * @return array ['type' => string|null, 'value' => float|null]
     */
    protected function resolveDiscountForCategoryIds(array $categoryIds): array
    {
        $categoryIds = array_values(array_unique(array_map('strval', $categoryIds)));

        // 1. Check EXCLUSIONS
        if (!empty($this->jasaniExcludedCategoryIds) && !empty(array_intersect($categoryIds, $this->jasaniExcludedCategoryIds))) {
            return ['type' => null, 'value' => null];
        }

        // 2. Check CATEGORY DISCOUNTS
        // We check all categories the product belongs to, and take the first one with an active offer.
        $categories = Category::whereIn('id', $categoryIds)->get();
        foreach ($categories as $category) {
            $offer = $category->activeOffer();
            if ($offer) {
                return [
                    'type' => $offer->discount_type,
                    'value' => (float) $offer->discount_value
                ];
            }
        }

        // 3. Fallback to GLOBAL setting
        if ($this->jasaniDiscountPercent > 0) {
            return [
                'type' => 'percent',
                'value' => $this->jasaniDiscountPercent
            ];
        }

        return ['type' => null, 'value' => null];
    }

    protected function applyDiscount(float $price, ?string $type, ?float $value): float
    {
        if (!$type || !$value || $price <= 0) {
            return $price;
        }

        $discountedPrice = $price;

        if ($type === 'percent') {
            $discountedPrice = $price - ($price * ($value / 100));
        } elseif ($type === 'fixed') {
            $discountedPrice = $price - $value;
        }

        return round(max(0, $discountedPrice), 2);
    }

    /* =====================================================
       CORE SYNC
    ===================================================== */

    protected function syncCatalog(array $data): void
    {
        collect($data)->groupBy('parent_id')->each(function ($variants) {

            $base = $variants->first();

            $brandId = ! empty($base['brand_id']) && is_array($base['brand_id'])
                ? $this->storeBrand($base['brand_id'])
                : null;

            // store categories and get UUID ids
            $categoryIds = $this->storeCategories($base['public_categ_ids'] ?? []);
            $this->storeTags($base['product_template_tags'] ?? []);

            $product = $this->storeProduct($base, $brandId, $categoryIds);

            /**
             * ✅ Discount decision per product, based on PRODUCT categories
             * Priority: Excluded > Category Offer > Global Fallback
             */
            $discountData = $this->resolveDiscountForCategoryIds(array_values($categoryIds));

            // Pre-resolve primary variant ID for this product group
            $primaryVariantId = $this->resolvePrimaryVariantId($variants, $base);

            foreach ($variants as $variantData) {

                $attributeValueIds = $this->storeAttributes(
                    $variantData['product_template_attribute_value_ids'] ?? []
                );

                $variant = $this->storeVariant(
                    $product,
                    $variantData,
                    $attributeValueIds,
                    $primaryVariantId,
                    $discountData
                );

                $this->storeVariantImages($variant, $variantData);
                $this->storeVariantPackaging($variant, $variantData);

                if (! empty($base['product_template_tags'])) {
                    $this->attachVariantTags($variant, $base['product_template_tags']);
                }
            }
        });
    }

    /* =====================================================
       MASTER DATA
    ===================================================== */

    protected function storeBrand(array $apiBrand): string
    {
        $referenceId = $apiBrand['id'] ?? null;
        $name = $apiBrand['name'] ?? null;

        if (! $referenceId || ! $name) {
            return '';
        }

        return Brand::updateOrCreate(
            ['reference_id' => $referenceId],
            [
                'name' => $name,
                'reference_name' => $name,
                'slug' => Str::slug($name),
                'is_active' => 1,
            ]
        )->id;
    }

    protected function storeCategories(array $categories): array
    {
        $ids = [];

        foreach ($categories as $cat) {

            $slug = Str::slug($cat['name']);

            if (Category::where('slug', $slug)->where('reference_id', '!=', $cat['id'])->exists()) {
                $slug .= '-' . $cat['id'];
            }

            $category = Category::updateOrCreate(
                ['reference_id' => $cat['id']],
                ['slug' => $slug, 'is_visible' => 1]
            );

            $category->translations()->updateOrCreate(
                ['locale' => 'en-ae'],
                ['name' => $cat['name'], 'reference_name' => $cat['name']]
            );

            // ✅ UUID id
            $ids[(string) $category->id] = (string) $category->id;
        }

        return $ids;
    }

    protected function storeTags(array $tags): void
    {
        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['reference_id' => $tag['id']],
                ['name' => $tag['name'], 'reference_name' => $tag['name'], 'is_active' => 1]
            );
        }
    }

    protected function storeAttributes(array $attrs): array
    {
        $ids = [];

        foreach ($attrs as $attr) {

            if (empty($attr['display_name']) || ! str_contains($attr['display_name'], ':')) {
                continue;
            }

            [$name, $value] = array_map('trim', explode(':', $attr['display_name'], 2));

            $attribute = Attribute::updateOrCreate(
                ['name' => $name],
                ['reference_name' => $name]
            );

            $valueModel = AttributeValue::updateOrCreate(
                ['reference_id' => $attr['id']],
                [
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                    'reference_value' => $value,
                ]
            );

            $ids[] = $valueModel->id;
        }

        return array_values(array_unique($ids));
    }

    /* =====================================================
       PRODUCT & VARIANT (PRICE + STOCK FIXED + DISCOUNT)
    ===================================================== */

    protected function storeProduct(array $apiProduct, ?string $brandId, array $categoryIds): Product
    {
        $refId = $apiProduct['parent_id'];

        $baseSlug = Str::slug($apiProduct['name']);
        $slug = $this->generateUniqueProductSlug($baseSlug, $refId);

        $product = Product::updateOrCreate(
            ['reference_id' => $refId],
            [
                'brand_id' => $brandId,
                'category_id' => $categoryIds ? array_key_first($categoryIds) : null,
                'slug' => $slug,
                'is_active' => 1,
            ]
        );

        $product->translations()->updateOrCreate(
            ['locale' => 'en-ae'],
            [
                'name' => $apiProduct['name'],
                'description' => $apiProduct['description_sale'] ?? null,
            ]
        );

        if ($categoryIds) {
            $product->categories()->sync($categoryIds);
        }

        return $product;
    }

    protected function generateUniqueProductSlug(string $baseSlug, int $referenceId): string
    {
        $existing = Product::where('reference_id', $referenceId)->first();
        if ($existing) {
            return $existing->slug;
        }

        $slug = $baseSlug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function storeVariant(
        Product $product,
        array $apiVariant,
        array $attributeValueIds,
        int $primaryVariantId,
        array $discountData
    ): ProductVariant {
        $refId = (int) ($apiVariant['id'] ?? 0);

        $price = 0.0;
        if (! empty($this->priceMap[$refId])) {
            $price = (float) $this->priceMap[$refId];
        }

        // ✅ Apply discount based on resolved priority
        $price = $this->applyDiscount($price, $discountData['type'] ?? null, $discountData['value'] ?? null);

        $stockData = $this->stockMap[$refId] ?? null;
        $netQty = (int) ($stockData['net_available_qty'] ?? 0);

        $variant = ProductVariant::updateOrCreate(
            ['reference_id' => $refId],
            [
                'product_id' => $product->id,
                'sku' => ($apiVariant['default_code'] ?? 'JASANI') . '-' . $refId,
                'price' => $price,
                'stock' => $netQty,
                'is_primary' => $refId === $primaryVariantId,
            ]
        );

        // Store detailed inventory
        if ($stockData) {
            $incomingDate = $stockData['incoming_date'] ?? null;
            if ($incomingDate === false) {
                $incomingDate = null;
            }

            Inventory::updateOrCreate(
                ['product_variant_id' => $variant->id],
                [
                    'blocked_qty' => (int) ($stockData['blocked_qty'] ?? 0),
                    'net_available_qty' => (int) ($stockData['net_available_qty'] ?? 0),
                    'incoming_qty' => (int) ($stockData['incoming_qty'] ?? 0),
                    'total_qty' => (int) ($stockData['total_qty'] ?? 0),
                    'incoming_date' => $incomingDate,
                ]
            );
        }

        if ($attributeValueIds) {
            $variant->attributeValues()->sync($attributeValueIds);
        }

        return $variant;
    }

    /**
     * Resolve the primary variant:
     * Small (with stock) -> Medium (with stock) -> Large (with stock)
     */
    protected function resolvePrimaryVariantId($variants, $parentVariant): int
    {
        $priorities = ['Small', 'Medium', 'Large'];

        foreach ($priorities as $size) {

            $bestRefId = null;
            $bestStock = 0;

            foreach ($variants as $v) {

                $refId = (int) ($v['id'] ?? 0);
                if (! $refId) continue;

                $stockData = $this->stockMap[$refId] ?? null;
                $stock = (int) ($stockData['net_available_qty'] ?? 0);

                if ($stock <= 0) continue;

                if ($this->variantHasSize($v, $size)) {
                    if ($stock > $bestStock) {
                        $bestStock = $stock;
                        $bestRefId = $refId;
                    }
                }
            }

            if ($bestRefId) return $bestRefId;
        }

        $bestRefId = null;
        $bestStock = 0;

        foreach ($variants as $v) {
            $refId = (int) ($v['id'] ?? 0);
            if (! $refId) continue;

            $stockData = $this->stockMap[$refId] ?? null;
            $stock = (int) ($stockData['net_available_qty'] ?? 0);

            if ($stock > $bestStock) {
                $bestStock = $stock;
                $bestRefId = $refId;
            }
        }

        if ($bestRefId) return $bestRefId;

        $parentId = (int) ($parentVariant['id'] ?? 0);

        if ($parentId) {
            foreach ($variants as $v) {
                if ((int) ($v['id'] ?? 0) === $parentId) {
                    return $parentId;
                }
            }
        }

        return (int) ($variants->first()['id'] ?? 0);
    }

    protected function variantHasSize(array $variant, string $size): bool
    {
        $attrs = $variant['product_template_attribute_value_ids'] ?? [];
        foreach ($attrs as $attr) {
            if (empty($attr['display_name'])) continue;

            $parts = explode(':', $attr['display_name']);
            if (count($parts) < 2) continue;

            $attrName = trim($parts[0]);
            $attrValue = trim($parts[1]);

            if (Str::lower($attrName) === 'size' && Str::lower($attrValue) === Str::lower($size)) {
                return true;
            }
        }

        return false;
    }

    /* =====================================================
       IMAGES
    ===================================================== */

    protected function storeVariantImages(ProductVariant $variant, array $data): void
    {
        $urls = [];

        if (! empty($data['image_url'])) {
            $urls[] = $data['image_url'];
        }

        foreach ($data['images'] ?? [] as $group) {
            foreach ($group as $img) {
                if (! empty($img['image_url'])) {
                    $urls[] = $img['image_url'];
                }
            }
        }

        foreach (array_unique($urls) as $url) {
            $variant->attachments()->firstOrCreate(
                ['file_name' => sha1($url)],
                ['file_path' => $url, 'file_type' => 'image']
            );
        }
    }

    /* =====================================================
       PACKAGING
    ===================================================== */

    protected function storeVariantPackaging(ProductVariant $variant, array $data): void
    {
        if (empty($data['specifications']['Packing'])) {
            return;
        }

        ProductVariantPackaging::where('product_variant_id', $variant->id)->delete();

        foreach ($data['specifications']['Packing'] as $row) {
            foreach ($row as $label => $value) {

                if (! $label || ! $value) continue;

                $packaging = Packaging::updateOrCreate(
                    ['reference_name' => trim($label)],
                    ['name' => trim($label), 'is_active' => 1]
                );

                ProductVariantPackaging::updateOrCreate(
                    [
                        'product_variant_id' => $variant->id,
                        'packaging_id' => $packaging->id,
                    ],
                    ['value' => trim($value)]
                );
            }
        }
    }

    /* =====================================================
       TAGS → VARIANT
    ===================================================== */

    protected function attachVariantTags(ProductVariant $variant, array $tags): void
    {
        $tagIds = Tag::whereIn('reference_id', collect($tags)->pluck('id'))->pluck('id')->toArray();
        $variant->tags()->sync($tagIds);
    }
}
