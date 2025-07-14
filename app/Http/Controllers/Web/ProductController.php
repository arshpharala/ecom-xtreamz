<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Catalog\Product;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductVariant;

class ProductController extends Controller
{

    function index() {}

    function show($slug)
    {
        $product = Product::whereSlug($slug)->firstOrFail();

        return $product;
    }

    function getProducts()
    {
        $locale = app()->getLocale();

        $products = Product::query()
            ->join('categories', function ($join) {
                $join->on('categories.id', 'products.category_id');
            })
            ->join('category_translations', function ($join) use ($locale) {
                $join->on('categories.id', 'category_translations.category_id')->where('category_translations.locale', $locale);
            })
            ->join('product_translations', function ($join) use ($locale) {
                $join->on('product_translations.product_id', 'products.id')->where('product_translations.locale', $locale);
            })
            ->leftJoin('brands', function ($join) {
                $join->on('brands.id', 'products.brand_id');
            })
            ->join('product_variants', function ($join) {
                $join->on('product_variants.product_id', 'products.id');
            })
            ->leftJoin('attachments as main_attachment', function ($join) {
                $join->on('main_attachment.attachable_id', 'product_variants.id')
                    ->where('main_attachment.attachable_type', ProductVariant::class)
                    ->whereRaw('ec_main_attachment.id = (
                SELECT a.id FROM ec_attachments a
                WHERE a.attachable_id = ec_product_variants.id
                  AND a.attachable_type = "' . addslashes(ProductVariant::class) . '"
                ORDER BY a.created_at ASC LIMIT 1
            )');
            })
            ->select(
                'products.id',
                'products.category_id',
                'products.slug',
                'products.position',
                'product_translations.name',
                'product_translations.description',
                'product_variants.id as product_variant_id',
                'product_variants.price',
                'product_variants.stock',
                'category_translations.name as category_name',
                'main_attachment.file_path',
                'main_attachment.file_name'
            )
            ->when(request()->filled('show_in_slider'), function ($query) {
                $query->where('products.show_in_slider', request()->show_in_slider);
            })
            ->when(request()->filled('category_id'), function ($query) {
                $query->where('products.category_id', request()->category_id);
            })
            ->when(request()->filled('limit'), function ($query) {
                $query->limit(request()->limit);
            })
            ->orderBy('products.position')
            ->groupBy('products.id')
            ->get()->map(function ($product) {
                $product->link = route('products.show', $product->slug);
                $product->image = $product->file_path ? asset('storage/' . $product->file_path) : null;
                return $product;
            });


        $data['products'] = $products;

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
