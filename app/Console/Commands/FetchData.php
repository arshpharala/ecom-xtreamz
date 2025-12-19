<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

/* ================= MODELS ================= */

use App\Models\Catalog\Brand;
use App\Models\Catalog\Category;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductVariant;
use App\Models\Catalog\Attribute;
use App\Models\Catalog\AttributeValue;
use App\Models\Catalog\ProductVariantPackaging;

use App\Models\CMS\Tag;
use App\Models\CMS\Packaging;

class FetchData extends Command
{
    protected $signature = 'app:fetch-data';
    protected $description = 'Safe Jasani catalog sync (products, variants, prices, stock, packaging, tags)';

    /** In-memory maps */
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
            $this->error('No products found.');
            return 1;
        }

        DB::transaction(fn() => $this->syncCatalog($products));

        $this->info('Catalog sync completed successfully.');
        return 0;
    }

    /* =====================================================
       LOADERS
    ===================================================== */

    protected function loadProducts(): array
    {
        $path = public_path('products.json');

        if (!file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true) ?? [];
    }

    protected function loadPrices(): void
    {
        $path = public_path('products-price.json');

        if (!file_exists($path)) {
            return;
        }

        foreach (json_decode(file_get_contents($path), true) ?? [] as $row) {
            if (!empty($row['id'])) {
                $this->priceMap[$row['id']] = (float) $row['price'];
            }
        }
    }

    protected function loadStock(): void
    {
        $path = public_path('product-stock.json');

        if (!file_exists($path)) {
            return;
        }

        foreach (json_decode(file_get_contents($path), true) ?? [] as $row) {
            if (!empty($row['id'])) {
                $this->stockMap[$row['id']] = (int) ($row['net_available_qty'] ?? 0);
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

            $brandId = !empty($base['brand_id'])
                ? $this->storeBrand($base['brand_id'])
                : null;

            $categoryIds = $this->storeCategories($base['public_categ_ids'] ?? []);
            $this->storeTags($base['product_template_tags'] ?? []);

            $product = $this->storeProduct($base, $brandId, $categoryIds);

            foreach ($variants as $variantData) {

                $attributeValueIds = $this->storeAttributes(
                    $variantData['product_template_attribute_value_ids'] ?? []
                );

                $variant = $this->storeVariant($product, $variantData, $attributeValueIds);

                $this->storeVariantImages($variant, $variantData);
                $this->storeVariantPackaging($variant, $variantData);

                if (!empty($base['product_template_tags'])) {
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
                'name'           => $name,
                'reference_name' => $name,
                'slug'           => Str::slug($name),
                'is_active'      => 1,
            ]
        )->id;
    }

    protected function storeCategories(array $categories): array
    {
        $ids = [];

        foreach ($categories as $cat) {
            $category = Category::updateOrCreate(
                ['reference_id' => $cat['id']],
                [
                    'slug'       => Str::slug($cat['name']),
                    'is_visible' => 1,
                ]
            );

            $category->translations()->updateOrCreate(
                ['locale' => 'en-ae'],
                [
                    'name'           => $cat['name'],
                    'reference_name' => $cat['name'],
                ]
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
                [
                    'name'           => $tag['name'],
                    'reference_name' => $tag['name'],
                    'is_active'      => 1,
                ]
            );
        }
    }

    protected function storeAttributes(array $attrs): array
    {
        $ids = [];

        foreach ($attrs as $attr) {

            if (!str_contains($attr['display_name'], ':')) {
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
                    'attribute_id'    => $attribute->id,
                    'value'           => $value,
                    'reference_value' => $value,
                ]
            );

            $ids[] = $valueModel->id;
        }

        return array_values(array_unique($ids));
    }

    /* =====================================================
       PRODUCT & VARIANT
    ===================================================== */

    protected function storeProduct(array $apiProduct, ?string $brandId, array $categoryIds): Product
    {
        $referenceId = $apiProduct['parent_id'];

        $product = Product::updateOrCreate(
            ['reference_id' => $referenceId],
            [
                'brand_id'    => $brandId,
                'category_id' => array_key_first($categoryIds),
                'slug'        => 'product-' . $referenceId,
                'is_active'   => 1,
            ]
        );

        $product->translations()->updateOrCreate(
            ['locale' => 'en-ae'],
            [
                'name'        => $apiProduct['name'],
                'description' => $apiProduct['description_sale'] ?? null,
            ]
        );

        if ($categoryIds) {
            $product->categories()->sync($categoryIds);
        }

        return $product;
    }

    protected function storeVariant(Product $product, array $apiVariant, array $attributeValueIds): ProductVariant
    {
        $refId = $apiVariant['id'];

        $variant = ProductVariant::updateOrCreate(
            ['reference_id' => $refId],
            [
                'product_id' => $product->id,
                'sku'        => ($apiVariant['default_code'] ?? 'JASANI') . '-' . $refId,
                'price'      => $this->priceMap[$refId] ?? 10,
                'stock'      => $this->stockMap[$refId] ?? 0,
            ]
        );

        if ($attributeValueIds) {
            $variant->attributeValues()->sync($attributeValueIds);
        }

        return $variant;
    }

    /* =====================================================
       IMAGES (URL ONLY)
    ===================================================== */

    protected function storeVariantImages(ProductVariant $variant, array $data): void
    {
        $urls = [];

        if (!empty($data['image_url'])) {
            $urls[] = $data['image_url'];
        }

        foreach ($data['images'] ?? [] as $group) {
            foreach ($group as $img) {
                if (!empty($img['image_url'])) {
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

                $packaging = Packaging::updateOrCreate(
                    ['reference_name' => trim($label)],
                    ['name' => trim($label), 'is_active' => 1]
                );

                ProductVariantPackaging::updateOrCreate(
                    [
                        'product_variant_id' => $variant->id,
                        'packaging_id'       => $packaging->id,
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
