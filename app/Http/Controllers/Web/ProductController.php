<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Catalog\Brand;
use Illuminate\Http\Response;
use App\Models\Catalog\Product;
use App\Models\Catalog\Category;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductVariant;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    function index()
    {

        $locale     = app()->getLocale();
        $categories = Category::leftJoin('category_translations', function ($join) use ($locale) {
            $join->on('category_translations.category_id', 'categories.id')->where('locale', $locale);
        })
            ->select('categories.id', 'categories.slug', 'categories.icon', 'categories.created_at', 'category_translations.name')
            ->orderBy('categories.position')
            ->get();


        $brands = Brand::active()->orderBy('position')->get();

        if (request()->filled('category_id')) {
            $activeCategory = $categories->where('id', request()->category_id)->first();
        }

        if (empty($activeCategory)) {
            $activeCategory = $categories->first();
        }


        $data['activeCategory'] = $activeCategory;
        $data['categories'] = $categories;
        $data['brands']     = $brands;

        return view('theme.xtremez.products', $data);;
    }




    public function show($slug, Request $request)
    {
        $variantId = $request->query('variant');

        $productVariant = $this->getProductVariant($variantId);
        $product = $this->getProductWithAttributes($productVariant->product_id);

        $attributes = $this->extractAttributesFromVariants($product);
        $selected = $this->getSelectedAttributes($productVariant);
        $allVariants = $this->formatAllVariants($product);

        return view('theme.xtremez.product-detail', compact(
            'productVariant',
            'attributes',
            'selected',
            'allVariants'
        ));
    }

    public function resolve(Request $request)
    {
        $productId = $request->input('product_id');
        $attributes = $request->input('attributes', []);

        $variant = $this->resolveVariant($productId, $attributes);

        if ($variant) {
            $variant->load(['product.translations', 'attachments', 'attributeValues.attribute', 'shipping']);

            return response()->json([
                'variant_id' => $variant->id,
                'sku' => $variant->sku,
                'slug' => $variant->product->slug,
                'name' => $variant->product->translation?->name ?? '',
                'description' => $variant->product->translation?->description ?? '',
                'price' => $variant->price,
                'stock' => $variant->stock,
                'images' => $variant->attachments->map(fn($a) => asset('storage/' . $a->file_path)),
                'file_path' => $variant->attachments->first()?->file_path,
                'shipping' => $variant->shipping,
                'combination' => collect($variant->attributeValues)->mapWithKeys(function ($val) {
                    return [Str::slug($val->attribute->name) => $val->value];
                }),
            ]);
        }

        return response()->json(['message' => 'No variant found'], 404);
    }



    protected function getProductVariant($variantId)
    {
        return ProductVariant::withJoins()
            ->select([
                'products.id as product_id',
                'products.slug',
                'products.category_id',
                'products.position',
                'product_variants.id',
                'product_variants.id as variant_id',
                'product_variants.sku',
                'product_variants.price',
                'product_variants.stock',
                'product_translations.name',
                'product_translations.description',
                'brands.name as brand_name',
                'category_translations.name as category_name',
                'main_attachment.file_path',
                'main_attachment.file_name'
            ])
            ->where('product_variants.id', $variantId)
            ->firstOrFail();
    }

    protected function getProductWithAttributes($productId)
    {
        return Product::with(['variants.attributeValues.attribute'])->findOrFail($productId);
    }

    protected function extractAttributesFromVariants($product)
    {
        $attributes = [];

        foreach ($product->variants as $variant) {
            foreach ($variant->attributeValues as $value) {
                $attrSlug = Str::slug($value->attribute->name);
                $attributes[$attrSlug]['name'] = $value->attribute->name;
                $attributes[$attrSlug]['values'][$value->value] = $value->value;
            }
        }

        return $attributes;
    }

    protected function getSelectedAttributes($productVariant)
    {
        $selected = [];

        foreach ($productVariant->attributeValues as $val) {
            $selected[Str::slug($val->attribute->name)] = $val->value;
        }

        return $selected;
    }

    protected function formatAllVariants($product)
    {
        $allVariants = [];

        foreach ($product->variants as $variant) {
            $combo = [];

            foreach ($variant->attributeValues as $val) {
                $slug = Str::slug($val->attribute->name);
                $combo[$slug] = $val->value;
            }

            $allVariants[] = [
                'id' => $variant->id,
                'slug' => $variant->product->slug,
                'combination' => $combo,
                'price' => $variant->price,
                'stock' => $variant->stock,
                'image' => $variant->attachments->first()?->file_path,
            ];
        }

        return $allVariants;
    }

    protected function resolveVariant($productId, $attributes)
    {
        $query = ProductVariant::with(['product.translations', 'attachments', 'attributeValues.attribute', 'shipping'])
            ->where('product_id', $productId);

        // Full match attempt
        foreach ($attributes as $attr => $val) {
            $query->whereHas('attributeValues', function ($q) use ($attr, $val) {
                $q->where('value', $val)
                    ->whereHas('attribute', function ($q2) use ($attr) {
                        $q2->whereRaw('LOWER(name) = ?', [strtolower($attr)]);
                    });
            });
        }

        $exact = $query->first();
        if ($exact) return $exact;

        // Fallback: only match last clicked attribute
        $lastAttr = array_key_last($attributes);
        $lastValue = $attributes[$lastAttr];

        return ProductVariant::with(['product.translations', 'attachments', 'attributeValues.attribute', 'shipping'])
            ->where('product_id', $productId)
            ->whereHas('attributeValues', function ($q) use ($lastAttr, $lastValue) {
                $q->where('value', $lastValue)
                    ->whereHas('attribute', function ($q2) use ($lastAttr) {
                        $q2->whereRaw('LOWER(name) = ?', [strtolower($lastAttr)]);
                    });
            })
            ->first();
    }










    public function getProducts()
    {
        $products = $this->repository->getFiltered();

        if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            return response()->json([
                'success' => true,
                'data' => [
                    'products' => $products->items(),
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'last_page' => $products->lastPage(),
                    ]
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $products
            ]
        ]);
    }

    public function getCategoryAttributes($categoryId)
    {
        $category = Category::with(['attributes.values'])->findOrFail($categoryId);

        $data = $category->attributes->map(function ($attr) {
            return [
                'id' => $attr->id,
                'name' => $attr->name,
                'values' => $attr->values->pluck('value', 'id'),
            ];
        });

        return response()->json(['success' => true, 'attributes' => $data]);
    }
}
