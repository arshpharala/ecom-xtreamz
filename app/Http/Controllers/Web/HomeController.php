<?php

namespace App\Http\Controllers\Web;

use App\Models\CMS\Page;
use App\Models\CMS\Banner;
use Illuminate\Http\Request;
use App\Models\Catalog\Brand;
use App\Models\Catalog\Category;
use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locale     = app()->getLocale();

        $categories = (new CategoryRepository())->getHomeScreenCategories(6);

        $giftSetProducts = (new ProductRepository())->getGiftProducts();
        $offers = (new \App\Repositories\OfferRepository())->getPromoOffers(5);

        $bannerOffers = $offers->where('show_in_slider', 1);
        $promoOffers = $offers->where('show_in_slider', 0)->take(1);


        $brands = Brand::whereNotNull('logo')->active()->orderBy('position')->get();

        $banners = Banner::active()
            ->with('translation')
            ->ordered()
            ->get();

        $data['banners']            = $banners;
        $data['locale']     = $locale;
        $data['categories'] = $categories;
        $data['brands']     = $brands;
        $data['giftSetProducts'] = $giftSetProducts;
        $data['promoOffers'] = $promoOffers;
        $data['bannerOffers'] = $bannerOffers;


        return view('theme.xtremez.home', $data);
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
