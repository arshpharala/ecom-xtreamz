<?php

namespace App\Http\Controllers\Web\V2;

use App\Models\CMS\Tag;
use App\Models\CMS\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Catalog\Brand;
use App\Services\CartService;
use Illuminate\Http\Response;
use App\Models\Catalog\Product;
use App\Models\Catalog\Category;
use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use App\Models\Catalog\ProductVariant;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    protected $repository;
    protected $cart;

    public function __construct(ProductRepository $repository, CartService $cart)
    {
        $this->repository = $repository;
        $this->cart = $cart;
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

        $slug = request()->segment(1);

        $page = Page::with('metas', 'translation')
            ->where('slug', $slug)
            ->active()
            ->first();



        $brands = Brand::active()->orderBy('position')->get();
        $tags = Tag::active()->orderBy('position')->get();

        if (request()->filled('category')) {
            $activeCategory = $categories->where('slug', request()->category)->first();
        }

        // if (empty($activeCategory)) {
        //     $activeCategory = $categories->first();
        // }


        $data['page']           = $page;
        $data['activeCategory'] = $activeCategory ?? null;
        $data['categories']     = $categories;
        $data['brands']         = $brands;
        $data['tags']           = $tags;

        return view('theme.medibazaar.products.index', $data);;
    }

    public function show($slug, Request $request)
    {
        $variantId      = $request->query('variant');
        $productVariant = $this->getProductVariant($variantId);
        $product        = $this->getProductWithAttributes($productVariant->product_id);
        $attributes     = $this->extractAttributesFromVariants($product);
        $selected       = $this->getSelectedAttributes($productVariant);
        // $allVariants    = $this->formatAllVariants($product);



        $featuredProducts = ProductVariant::withJoins()
            ->applySorting('position')
            ->withSelection()
            ->withFilters(['is_featured' => 1])
            ->limit(8)
            ->get()->map(function ($variant) {
                return (new ProductRepository())->transform($variant);
            });

        $data['featuredProducts']   = $featuredProducts;
        $data['productVariant']     = $productVariant;
        $data['attributes']         = $attributes;
        $data['selected']           = $selected;

        return view('theme.medibazaar.products.show',$data);
    }

    public function resolve(Request $request)
    {
        $productId  = $request->input('product_id');
        $attributes = $request->input('attributes', []);

        $variant = $this->resolveVariant($productId, $attributes);

        if (!$variant) {
            return response()->json(['message' => 'No variant found'], 404);
        }

        $variant = ProductVariant::withJoins()
            ->withSelection()
            ->where('product_variants.id', $variant->id)
            ->with(['shipping', 'attachments', 'attributeValues.attribute', 'offers'])
            ->firstOrFail();

        $variant                = $this->repository->transform($variant);
        $variant->images        = $variant->attachments->map(fn($a) => asset('storage/' . $a->file_path));
        $variant->combination   = collect($variant->attributeValues)
            ->mapWithKeys(function ($val) {
                return [Str::slug($val->attribute->name) => $val->value];
            });

        return response()->json($variant);
    }

    protected function getProductVariant($variantId)
    {
        $variant =  ProductVariant::withJoins()
            ->withSelection()
            ->where('product_variants.id', $variantId)
            ->firstOrFail();

        $variant = $this->repository->transform($variant);

        return $variant;
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
                'cart_item' => $this->cart->getItem($variant->id)
            ];
        }

        return $allVariants;
    }

    protected function resolveVariant($productId, $attributes)
    {
        $query = ProductVariant::where('product_id', $productId);

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

        $lastAttr = array_key_last($attributes);
        $lastValue = $attributes[$lastAttr];

        return ProductVariant::where('product_id', $productId)
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

    function clearance()
    {
        $slug           = request()->segment(1);
        $page           = (new PageRepository())->findOrFailBySlug($slug);
        $data['page']   = $page;

        return view('theme.xtremez.products.clearance', $data);
    }

    function featured()
    {
        $slug               = request()->segment(1);
        $page               = (new PageRepository())->findOrFailBySlug($slug);
        $giftSetProducts    = (new ProductRepository())->getGiftProducts();

        $data['giftSetProducts']   = $giftSetProducts;
        $data['page']   = $page;

        return view('theme.xtremez.products.featured', $data);
    }
}
