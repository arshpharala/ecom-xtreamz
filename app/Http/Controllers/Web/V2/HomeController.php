<?php

namespace App\Http\Controllers\Web\V2;

use App\Models\Catalog\Brand;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Offer;
use App\Models\Catalog\ProductVariant;
use App\Models\CMS\News;
use App\Models\CMS\Testimonial;
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


        $bannerProducts = ProductVariant::withJoins()
            ->applySorting('position')
            ->withFilters(['show_in_slider' => 1])
            ->withSelection()
            ->limit(6)
            ->get()->map(function ($variant) {
                return (new ProductRepository())->transform($variant);
            });


        $bestSellerProducts = ProductVariant::withJoins()
            ->applySorting('position')
            ->withSelection()
            ->withFilters(['tags' => ['Best Seller']])
            ->limit(6)
            ->get()->map(function ($variant) {
                return (new ProductRepository())->transform($variant);
            });

        $topRatedProducts = ProductVariant::withJoins()
            ->applySorting('position')
            ->withSelection()
            ->withFilters(['tags' => ['Top Rated']])
            ->limit(6)
            ->get()->map(function ($variant) {
                return (new ProductRepository())->transform($variant);
            });

        $popularProducts = ProductVariant::withJoins()
            ->applySorting('position')
            ->withSelection()
            ->withFilters(['tags' => ['Popular']])
            ->limit(6)
            ->get()->map(function ($variant) {
                return (new ProductRepository())->transform($variant);
            });

        $deal = Offer::active()->first();

        $dealProducts = ProductVariant::withJoins()
            ->applySorting('position')
            ->withSelection()
            ->withFilters(['offer' => true, 'offer_id' => $deal->id])
            ->limit(3)
            ->get()->map(function ($variant) {
                return (new ProductRepository())->transform($variant);
            });

        $featuredProducts = ProductVariant::withJoins()
            ->applySorting('position')
            ->withSelection()
            ->withFilters(['is_featured' => 1])
            ->limit(8)
            ->get()->map(function ($variant) {
                return (new ProductRepository())->transform($variant);
            });

        $testimonials = Testimonial::query()
            ->where('is_active', 1)
            ->orderBy('position', 'desc')
            ->get();

        $news = News::with('translation')
            ->where('is_active', 1)
            ->orderBy('position', 'desc')
            ->limit(3)->get();

        $data['bannerProducts']     = $bannerProducts;
        $data['bestSellerProducts'] = $bestSellerProducts;
        $data['topRatedProducts']   = $topRatedProducts;
        $data['popularProducts']    = $popularProducts;
        $data['deal']               = $deal;
        $data['dealProducts']       = $dealProducts;
        $data['featuredProducts']   = $featuredProducts;
        $data['testimonials']       = $testimonials;
        $data['news']               = $news;


        $brands = Brand::whereNotNull('logo')->active()->orderBy('position')->get();

        $data['locale']     = $locale;
        $data['brands']     = $brands;

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
