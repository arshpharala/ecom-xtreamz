<?php

namespace App\Http\Controllers\Web;

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

    // public function show($slug, Request $request)
    // {
    //     $variantId = $request->query('variant');

    //     // Try to fetch the specific variant if UUID is passed
    //     $variant = ProductVariant::with([
    //         'product.translations',
    //         'product.brand',
    //         'product.category.translations',
    //         'attributeValues.attribute',
    //         'attachments',
    //         'shipping'
    //     ])
    //         ->when($variantId, function ($query) use ($variantId) {
    //             $query->where('id', $variantId);
    //         }, function ($query) use ($slug) {
    //             $query->whereHas('product', function ($q) use ($slug) {
    //                 $q->where('slug', $slug);
    //             })->orderBy('created_at'); // fallback to first variant
    //         })
    //         ->firstOrFail();

    //     $product = $variant->product;

    //     // Group attributes for swatches
    //     $groupedAttributes = $product->variants()
    //         ->with('attributeValues.attribute')
    //         ->get()
    //         ->flatMap(function ($variant) {
    //             return $variant->attributeValues;
    //         })
    //         ->groupBy('attribute.name');

    //     // Related products from same category
    //     $relatedProducts = ProductVariant::with([
    //         'product.translations',
    //         'attachments'
    //     ])
    //         ->whereHas('product', function ($q) use ($product) {
    //             $q->where('category_id', $product->category_id)
    //                 ->where('id', '!=', $product->id);
    //         })
    //         ->take(10)
    //         ->get();


    //     return view('theme.xtremez.product-detail', compact('variant', 'product', 'groupedAttributes', 'relatedProducts'));
    // }
    public function show($slug, Request $request)
    {
        $variantId = $request->query('variant');

        $product = ProductVariant::withJoins()
        ->select([
                'products.id as product_id',
                'products.slug',
                'products.category_id',
                'products.position',
                'product_variants.id',
                'product_variants.id as variant_id',
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


            $data['product'] = $product->load('attachments');

        return view('theme.xtremez.product-detail', $data);
    }

    function getVariant(string $productId, string $uuid)
    {
        $variant = ProductVariant::with([
            'attributeValues.attribute',
            'attachments',
            'shipping',
        ])->findOrFail($uuid);

        return response()->json([
            'data' => [
                'variant' => [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                    'images' => $variant->attachments->map(fn($a) => asset('storage/' . $a->file_path)),
                    'attributes' => $variant->attributeValues->map(fn($av) => [
                        'attribute' => $av->attribute->name,
                        'value' => $av->value,
                    ]),
                ],
            ],
        ]);
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
