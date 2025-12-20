<?php

namespace App\Http\Controllers\Web;

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
use App\Models\CMS\Tag;
use App\Repositories\ProductVariantRepository;

class ProductController extends Controller
{
    protected $repository;
    protected $cart;

    public function __construct(ProductVariantRepository $repository, CartService $cart)
    {
        $this->repository = $repository;
        $this->cart = $cart;
    }

    function sidebarCategories()
    {
        return Category::visible()
            ->withJoins()
            ->withSelection()
            ->whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->visible()->applySorting('position')->with('translation');
            }])
            ->applySorting('position')
            ->get();
    }
    function index()
    {
        $locale     = app()->getLocale();
        $categories = Category::visible()->leftJoin('category_translations', function ($join) use ($locale) {
            $join->on('category_translations.category_id', 'categories.id')->where('locale', $locale);
        })
            ->select('categories.id', 'categories.slug', 'categories.icon', 'categories.created_at', 'category_translations.name')
            ->orderBy('categories.position')
            ->get();


        $brands = Brand::active()->orderBy('position')->get();
        $tags = Tag::active()->orderBy('position')->get();

        if (request()->filled('category')) {
            $activeCategory = $categories->where('slug', request()->category)->first();
        }

        if (request()->filled('category_id')) {
            $activeCategory = $categories->where('id', request()->category_id)->first();
        }

        // if (empty($activeCategory)) {
        //     $activeCategory = $categories->first();
        // }

        $data['sidebarCategories'] = $this->sidebarCategories();
        $data['activeCategory'] = $activeCategory ?? null;
        $data['categories']     = $categories;
        $data['brands']         = $brands;
        $data['tags']           = $tags;

        return view('theme.xtremez.products.index', $data);;
    }

    public function show($slug, $variantId, Request $request)
    {
        // $variantId      = $request->query('variant');
        $productVariant = $this->getProductVariant($variantId);
        $product        = $this->getProductWithAttributes($productVariant->product_id);
        $attributes     = $this->extractAttributesFromVariants($product);
        $selected       = $this->getSelectedAttributes($productVariant);
        // $allVariants    = $this->formatAllVariants($product);

        $data['productVariant'] = $productVariant;
        $data['product']        = $product;
        $data['attributes']        = $attributes;
        $data['selected']       = $selected;
        $data['meta']           = $product ? $product->metaForLocale() :  null;

        return view('theme.xtremez.products.show', $data);
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
            ->with(['packagings', 'attachments', 'attributeValues.attribute', 'offers'])
            ->firstOrFail();

        $variant                = $this->repository->transform($variant);
        $variant->images        = $variant->attachments->map(fn($a) => get_attachment_url($a->file_path));
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
        $products = $this->repository->getFiltered(request()->per_page);

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
        $giftSetProducts    = (new ProductVariantRepository())->getGiftProducts();

        $data['giftSetProducts']   = $giftSetProducts;
        $data['page']   = $page;

        return view('theme.xtremez.products.featured', $data);
    }
}
