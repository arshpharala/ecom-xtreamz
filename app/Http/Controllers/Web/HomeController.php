<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\CMS\Page;
use App\Models\CMS\Banner;
use Illuminate\Http\Request;
use App\Models\Catalog\Brand;
use App\Models\Catalog\Category;
use App\Models\Sales\Subscriber;
use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\RateLimiter;
use App\Repositories\ProductVariantRepository;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locale     = app()->getLocale();

        $categories = (new CategoryRepository())->getHomeScreenCategories(6);

        $giftSetProducts = (new ProductVariantRepository())->getGiftProducts();
        $offers = (new \App\Repositories\OfferRepository())->getPromoOffers(5);

        $bannerOffers = $offers->where('show_in_slider', 1);
        $promoOffers = $offers->where('show_in_slider', 0)->take(1);


        $brands = Brand::whereNotNull('logo')->active()->orderBy('position')->get();

        $banners = Banner::active()
            ->with('translation')
            ->ordered()
            ->get();

        $page = (new PageRepository())->findBySlug('home');

        $data['page']               = $page->load('metas', 'translation');
        $data['meta']               = $page ? $page->metaForLocale() :  null;
        $data['banners']            = $banners;
        $data['locale']             = $locale;
        $data['categories']         = $categories;
        $data['brands']             = $brands;
        $data['giftSetProducts']    = $giftSetProducts;
        $data['promoOffers']        = $promoOffers;
        $data['bannerOffers']       = $bannerOffers;


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

    public function subscribe(Request $request)
    {
        // Throttle per IP (5 requests/minute)
        $key = 'subscribe:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['message' => __('Too many attempts. Try again later.')], 429);
        }
        RateLimiter::hit($key, 60);

        // Validate request (with honeypot)
        $data = $request->validate([
            'subscriber_email' => 'required|email|unique:subscribers,email',
            'extra_field'      => 'max:0' // honeypot (must stay empty)
        ]);

        $email = $data['subscriber_email'];

        // Check MX record
        $domain = substr(strrchr($email, "@"), 1);
        if (!checkdnsrr($domain, "MX")) {
            return response()->json(['message' => __('Invalid email domain.')], 422);
        }

        // Find user (if exists)
        $userId = User::where('email', $email)->value('id');

        Subscriber::create([
            'user_id'      => $userId,
            'email'        => $email,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'subscribed_at' => now()
        ]);

        return response()->json(['message' => __('Subscribed successfully')]);
    }
}
