<?php

namespace App\Console\Commands;

use App\Models\Catalog\Attribute;
use App\Models\Catalog\AttributeValue;
use App\Models\Catalog\Brand;
/* ================= MODELS ================= */
use App\Models\Catalog\Category;
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

    /* =====================================================
       HANDLE (ORCHESTRATION ONLY)
    ===================================================== */

    public function handle()
    {
        ini_set('max_execution_time', '300');

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
            DB::transaction(fn () => $this->syncCatalog($products));

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
                $this->priceMap[(int) $row['id']] = (float) $row['retail_price']; // Now api sending retail_price
            }
        }
    }

    protected function loadStock(): void
    {
        $path = Storage::disk('local')->path('jasani-product-stock.json');

        if (! file_exists($path)) {
            return;
        }

        $json = json_decode(file_get_contents($path), true);

        foreach ($json['data'] ?? [] as $row) {
            if (! empty($row['id'])) {
                $this->stockMap[(int) $row['id']] = (int) ($row['net_available_qty'] ?? 0);
            }
        }
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

            $categoryIds = $this->storeCategories($base['public_categ_ids'] ?? []);
            $this->storeTags($base['product_template_tags'] ?? []);

            $product = $this->storeProduct($base, $brandId, $categoryIds);

            // Pre-resolve primary variant ID for this product group
            $primaryVariantId = $this->resolvePrimaryVariantId($variants, $base);

            foreach ($variants as $variantData) {

                $attributeValueIds = $this->storeAttributes(
                    $variantData['product_template_attribute_value_ids'] ?? []
                );

                $variant = $this->storeVariant($product, $variantData, $attributeValueIds, $primaryVariantId);

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
        [$referenceId, $name] = $apiBrand;

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

            $category = Category::updateOrCreate(
                ['reference_id' => $cat['id']],
                ['slug' => Str::slug($cat['name']), 'is_visible' => 1]
            );

            $category->translations()->updateOrCreate(
                ['locale' => 'en-ae'],
                ['name' => $cat['name'], 'reference_name' => $cat['name']]
            );

            $ids[$category->id] = $category->id;
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
       PRODUCT & VARIANT (PRICE + STOCK FIXED)
    ===================================================== */

    protected function storeProduct(array $apiProduct, ?string $brandId, array $categoryIds): Product
    {
        $refId = $apiProduct['parent_id'];

        // 1️⃣ Generate base slug from product name
        $baseSlug = Str::slug($apiProduct['name']);

        // 2️⃣ Resolve unique slug (safe for re-sync)
        $slug = $this->generateUniqueProductSlug($baseSlug, $refId);

        // 3️⃣ Store / Update product
        $product = Product::updateOrCreate(
            ['reference_id' => $refId],
            [
                'brand_id' => $brandId,
                'category_id' => $categoryIds ? array_key_first($categoryIds) : null,
                'slug' => $slug,
                'is_active' => 1,
            ]
        );

        // 4️⃣ Translation
        $product->translations()->updateOrCreate(
            ['locale' => 'en-ae'],
            [
                'name' => $apiProduct['name'],
                'description' => $apiProduct['description_sale'] ?? null,
            ]
        );

        // 5️⃣ Categories
        if ($categoryIds) {
            $product->categories()->sync($categoryIds);
        }

        return $product;
    }

    protected function generateUniqueProductSlug(string $baseSlug, int $referenceId): string
    {
        // Check if product already exists (same reference_id)
        $existing = Product::where('reference_id', $referenceId)->first();

        if ($existing) {
            return $existing->slug;
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            Product::where('slug', $slug)->exists()
        ) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    protected function storeVariant(Product $product, array $apiVariant, array $attributeValueIds, int $primaryVariantId): ProductVariant
    {
        $refId = (int) $apiVariant['id'];
        $price = 0;
        if (! empty($this->priceMap[$refId])) {
            $price = $this->priceMap[$refId]; // Use loaded price map
        }

        $variant = ProductVariant::updateOrCreate(
            ['reference_id' => $refId],
            [
                'product_id' => $product->id,
                'sku' => ($apiVariant['default_code'] ?? 'JASANI').'-'.$refId,
                'price' => $price,
                'stock' => $this->stockMap[$refId] ?? 0,
                'is_primary' => $refId === $primaryVariantId,
            ]
        );

        if ($attributeValueIds) {
            $variant->attributeValues()->sync($attributeValueIds);
        }

        return $variant;
    }

    /**
     * Resolve the best primary variant based on size and stock.
     */
    protected function resolvePrimaryVariantId($variants, $parentVariant): int
    {
        $priorities = ['Small', 'Medium', 'Large', 'XL', 'XXL', '3XL', '4XL'];

        // 1. Try to find a variant matching priority size AND having stock
        foreach ($priorities as $size) {
            foreach ($variants as $v) {
                $refId = (int) $v['id'];
                $stock = $this->stockMap[$refId] ?? 0;

                if ($stock > 0 && $this->variantHasSize($v, $size)) {
                    return $refId;
                }
            }
        }

        // 2. Fallback to parent variant ID if it exists in the list
        $parentId = (int) $parentVariant['id'];
        foreach ($variants as $v) {
            if ((int) $v['id'] === $parentId) {
                return $parentId;
            }
        }

        // 3. Fallback to any variant with size 'Small'
        foreach ($variants as $v) {
            if ($this->variantHasSize($v, 'Small')) {
                return (int) $v['id'];
            }
        }

        // 4. Final fallback: first variant in the list
        return (int) $variants->first()['id'];
    }

    /**
     * Check if variant has a specific size in its attributes.
     */
    protected function variantHasSize(array $variant, string $size): bool
    {
        $attrs = $variant['product_template_attribute_value_ids'] ?? [];
        foreach ($attrs as $attr) {
            if (empty($attr['display_name'])) {
                continue;
            }

            $parts = explode(':', $attr['display_name']);
            if (count($parts) < 2) {
                continue;
            }

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

                if (! $label || ! $value) {
                    continue;
                }

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
