<?php

namespace App\Http\Controllers\Web\V2;

use App\Models\CMS\Page;
use Illuminate\Http\Request;
use App\Models\Catalog\Brand;
use App\Models\Catalog\Category;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductVariant;
use App\Repositories\PageRepository;
use App\Repositories\ProductRepository;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locale     = app()->getLocale();


        $bannerProducts = ProductVariant::where('show_in_slider', 1)->where('is_active', 1)
            ->with('product.translation', 'product.category.translation')->limit(6)->get();

        $data['bannerProducts'] = $bannerProducts;


        $categories = Category::leftJoin('category_translations', function ($join) use ($locale) {
            $join->on('category_translations.category_id', 'categories.id')->where('locale', $locale);
        })
            ->select('categories.id', 'categories.slug', 'categories.icon', 'categories.created_at', 'category_translations.name')
            ->orderBy('categories.position')
            ->has('products')
            // ->limit(6)
            ->get();





        $giftSetProducts = (new ProductRepository())->getGiftProducts();


        $brands = Brand::whereNotNull('logo')->active()->orderBy('position')->get();


        $data['locale']     = $locale;
        $data['categories'] = $categories;
        $data['brands']     = $brands;
        $data['giftSetProducts'] = $giftSetProducts;

        return view('theme.medibazaar.home', $data);
    }


    public function page()
    {
        $slug = request()->segment(1);

        $page = (new PageRepository())->findOrFailBySlug($slug);

        $data['page'] = $page;

        return view('theme.xtremez.page', $data);
    }

    function currencies()
    {
        $currencies = \App\Models\CMS\Currency::all()->keyBy('code')->map(function ($currency) {
            return [
                'symbol'            => $currency->symbol,
                'decimal'           => (int) $currency->decimal,
                'decimal_separator' => $currency->decimal_separator,
                'group_separator'   => $currency->group_separator,
                'currency_position' => $currency->currency_position,
            ];
        });

        return response()->json(['data' => $currencies]);
    }
}
