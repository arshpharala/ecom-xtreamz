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
use App\Models\Catalog\AttributeValue;

class ProductController extends Controller
{
    protected $repository;
    protected $cart;

    public function __construct(ProductVariantRepository $repository, CartService $cart)
    {
        $this->repository = $repository;
        $this->cart = $cart;
    }

    /* ======================================================
        LISTING
    ====================================================== */

    function sidebarCategories()
    {
        return Category::visible()
            ->withJoins()
            ->withSelection()
            ->whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->visible()
                    ->applySorting('position')
                    ->with(['translation', 'children' => function ($q2) {
                        $q2->visible()->applySorting('position')->with('translation');
                    }]);
            }])
            ->applySorting('position')
            ->get();
    }

    function index()
    {
        $locale = app()->getLocale();

        $categories = Category::visible()
            ->leftJoin('category_translations', function ($join) use ($locale) {
                $join->on('category_translations.category_id', 'categories.id')->where('locale', $locale);
            })
            ->select('categories.id', 'categories.slug', 'categories.icon', 'categories.created_at', 'category_translations.name')
            ->orderBy('categories.position')
            ->get();

        $brands = Brand::active()->orderBy('position')->get();
        $tags   = Tag::active()->orderBy('position')->get();

        if (request()->filled('category')) {
            $activeCategory = $categories->where('slug', request()->category)->first();
        }

        if (request()->filled('category_id')) {
            $activeCategory = $categories->where('id', request()->category_id)->first();
        }

        $data['sidebarCategories'] = $this->sidebarCategories();
        $data['activeCategory']    = $activeCategory ?? null;
        $data['categories']        = $categories;
        $data['brands']            = $brands;
        $data['tags']              = $tags;

        return view('theme.xtremez.products.index', $data);
    }

    /* ======================================================
        PRODUCT DETAIL
    ====================================================== */

    public function show($slug, $variantId, Request $request)
    {
        $productVariant = $this->getProductVariant($variantId);
        $product        = $this->getProductWithAttributes($productVariant->product_id);

        $attributes     = $this->extractAttributesFromVariants($product);
        $selected       = $this->getSelectedAttributes($productVariant);
        $allVariants    = $this->formatAllVariants($product);

        $data['productVariant'] = $productVariant;
        $data['product']        = $product;
        $data['attributes']     = $attributes;
        $data['selected']       = $selected;
        $data['allVariants']    = $allVariants;

        if (empty($product->metaForLocale()->meta_title)) {
            $data['meta'] = (object)[
                'meta_title' => $productVariant->name,
                'meta_description' => Str::limit($productVariant->description, 160)
            ];
        } else {
            $data['meta'] = $product ? $product->metaForLocale() : null;
        }

        return view('theme.xtremez.products.show', $data);
    }

    /**
     * Variant Resolver Endpoint (Amazon-style)
     * If product has Size:
     * - Keep other attributes fixed (e.g. color = Grey)
     * - Always choose best Size by priority + stock:
     *   Small -> Medium -> Large -> XL -> XXL -> 3X-Large -> 4X-Large
     */
    public function resolve(Request $request)
    {
        $productId  = (int) $request->input('product_id');
        $attributes = $request->input('attributes', []);

        $variant = $this->resolveVariant($productId, $attributes);

        if (! $variant) {
            return response()->json(['message' => 'No variant found'], 404);
        }

        $variant = ProductVariant::withJoins()
            ->withSelection()
            ->where('product_variants.id', $variant->id)
            ->with(['packagings', 'attachments', 'attributeValues.attribute', 'offers'])
            ->firstOrFail();

        $variant              = $this->repository->transform($variant);
        $variant->images      = $variant->attachments->map(fn($a) => get_attachment_url($a->file_path));

        // ✅ Combination sorting: Color first, then Size, then others
        $variant->combination = collect($variant->attributeValues)
            ->sortBy(function ($val) {
                $name = Str::lower($val->attribute->name);

                if ($name === 'color') return 0;
                if ($name === 'size')  return 1;

                return 2;
            })
            ->mapWithKeys(function ($val) {
                return [Str::slug($val->attribute->name) => $val->value];
            });

        return response()->json($variant);
    }

    /* ======================================================
        LOAD HELPERS
    ====================================================== */

    protected function getProductVariant($variantId)
    {
        $variant = ProductVariant::withJoins()
            ->withSelection()
            ->where('product_variants.id', $variantId)
            ->firstOrFail();

        return $this->repository->transform($variant);
    }

    protected function getProductWithAttributes($productId)
    {
        return Product::with(['variants.attributeValues.attribute'])->findOrFail($productId);
    }

    /* ======================================================
        ATTRIBUTE UI HELPERS
    ====================================================== */

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

        // 1) Sort values inside each attribute
        foreach ($attributes as $slug => &$attr) {
            $values = $attr['values'];

            if (Str::lower($attr['name']) === 'size') {
                // Custom sort for sizes
                uksort($values, function ($a, $b) {
                    $weightA = AttributeValue::getSizeSortWeight($a);
                    $weightB = AttributeValue::getSizeSortWeight($b);

                    if ($weightA == $weightB) return strcmp($a, $b);
                    return $weightA - $weightB;
                });
            } else {
                // Default alphabetical
                ksort($values);
            }

            $attr['values'] = $values;
        }

        // 2) Sort attributes: Color always first
        uksort($attributes, function ($a, $b) use ($attributes) {
            $nameA = Str::lower($attributes[$a]['name']);
            $nameB = Str::lower($attributes[$b]['name']);

            if ($nameA === 'color') return -1;
            if ($nameB === 'color') return 1;

            return strcmp($nameA, $nameB);
        });

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

    /**
     * ✅ All variants formatter
     * If product has Size => return variants sorted like:
     * - In-stock first
     * - Size order (Small -> Medium -> Large -> XL -> ...)
     */
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

        // ✅ If Size exists, apply sort rules
        $hasSize = collect($allVariants)->contains(function ($v) {
            return isset($v['combination']['size']);
        });

        if ($hasSize) {
            usort($allVariants, function ($a, $b) {

                $stockA = (int) ($a['stock'] ?? 0);
                $stockB = (int) ($b['stock'] ?? 0);

                $inStockA = $stockA > 0 ? 1 : 0;
                $inStockB = $stockB > 0 ? 1 : 0;

                // 1) In-stock always first
                if ($inStockA !== $inStockB) {
                    return $inStockB <=> $inStockA;
                }

                // 2) Sort by size weight
                $sizeA = $a['combination']['size'] ?? null;
                $sizeB = $b['combination']['size'] ?? null;

                $weightA = AttributeValue::getSizeSortWeight($sizeA);
                $weightB = AttributeValue::getSizeSortWeight($sizeB);

                if ($weightA === $weightB) {
                    return strcmp((string) $sizeA, (string) $sizeB);
                }

                return $weightA <=> $weightB;
            });
        }

        return $allVariants;
    }

    /* ======================================================
        ✅ VARIANT RESOLVE LOGIC (YOUR REQUIRED RULE)
    ====================================================== */

    protected function resolveVariant($productId, $attributes)
    {
        // Normalize keys to lowercase (size, color...)
        $attributes = collect($attributes)
            ->mapWithKeys(fn($v, $k) => [strtolower($k) => $v])
            ->toArray();

        // Check if product has "size" attribute at all
        $hasSize = ProductVariant::where('product_id', $productId)
            ->whereHas('attributeValues.attribute', function ($q) {
                $q->whereRaw('LOWER(name) = ?', ['size']);
            })
            ->exists();

        /**
         * ✅ If has size:
         * Keep other attributes fixed (color stays Grey)
         * Auto-select the best size:
         * Small -> Medium -> Large -> XL -> ...
         */
        if ($hasSize) {
            $baseAttrs = collect($attributes)->except(['size'])->toArray();

            $preferred = $this->findPreferredSizeVariant($productId, $baseAttrs);

            if ($preferred) {
                return $preferred;
            }
        }

        /**
         * ✅ Fallback to exact match
         */
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
        if ($exact) {
            return $exact;
        }

        /**
         * ✅ Final fallback: match only last attribute
         */
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

    /**
     * ✅ Returns best variant based on Size priority + Stock.
     * This guarantees:
     * Grey + Small (if stock) else Grey + Medium else Grey + Large ...
     */
    protected function findPreferredSizeVariant(int $productId, array $baseAttrs): ?ProductVariant
    {
        $priorities = ['Small', 'Medium', 'Large', 'XL', 'XXL', '3X-Large', '4X-Large'];

        // ✅ 1) First pass: Size priority WITH stock > 0
        foreach ($priorities as $size) {

            $query = ProductVariant::where('product_id', $productId)
                ->where('stock', '>', 0);

            // Apply base attributes (example: color = Grey)
            foreach ($baseAttrs as $attr => $val) {
                $query->whereHas('attributeValues', function ($q) use ($attr, $val) {
                    $q->where('value', $val)
                        ->whereHas('attribute', function ($q2) use ($attr) {
                            $q2->whereRaw('LOWER(name) = ?', [strtolower($attr)]);
                        });
                });
            }

            // Apply size condition
            $query->whereHas('attributeValues', function ($q) use ($size) {
                $q->where('value', $size)
                    ->whereHas('attribute', function ($q2) {
                        $q2->whereRaw('LOWER(name) = ?', ['size']);
                    });
            });

            $variant = $query->first();

            if ($variant) {
                return $variant;
            }
        }

        // ✅ 2) Second pass: Size priority WITHOUT stock (if all are out of stock)
        foreach ($priorities as $size) {

            $query = ProductVariant::where('product_id', $productId);

            foreach ($baseAttrs as $attr => $val) {
                $query->whereHas('attributeValues', function ($q) use ($attr, $val) {
                    $q->where('value', $val)
                        ->whereHas('attribute', function ($q2) use ($attr) {
                            $q2->whereRaw('LOWER(name) = ?', [strtolower($attr)]);
                        });
                });
            }

            $query->whereHas('attributeValues', function ($q) use ($size) {
                $q->where('value', $size)
                    ->whereHas('attribute', function ($q2) {
                        $q2->whereRaw('LOWER(name) = ?', ['size']);
                    });
            });

            $variant = $query->first();

            if ($variant) {
                return $variant;
            }
        }

        return null;
    }

    /* ======================================================
        AJAX PRODUCTS
    ====================================================== */

    public function getProducts()
    {
        $products = $this->repository->getFiltered(request()->per_page ?? request()->limit);

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

    /* ======================================================
        PAGES
    ====================================================== */

    function clearance()
    {
        $slug         = request()->segment(1);
        $page         = (new PageRepository())->findOrFailBySlug($slug);
        $data['page'] = $page;

        return view('theme.xtremez.products.clearance', $data);
    }

    function featured()
    {
        $slug            = request()->segment(1);
        $page            = (new PageRepository())->findOrFailBySlug($slug);
        $giftSetProducts = (new ProductVariantRepository())->getGiftProducts();

        $data['giftSetProducts'] = $giftSetProducts;
        $data['page']            = $page;

        return view('theme.xtremez.products.featured', $data);
    }

    public function checkMultipleVariants(Request $request)
    {
        $variantId = $request->input('variant_id');
        $productId = $this->repository->getProductIdFromVariantId($variantId);

        if (! $productId) {
            return response()->json(['error' => 'Variant not found'], 404);
        }

        $hasMultipleVariants = $this->repository->hasMultipleVariants($productId);

        if ($hasMultipleVariants) {
            $product = Product::find($productId);
            $productUrl = route('products.show', ['slug' => $product->slug, 'variant' => $variantId]);
        } else {
            $productUrl = null;
        }

        return response()->json([
            'has_multiple_variants' => $hasMultipleVariants,
            'product_url' => $productUrl
        ]);
    }
}
