<?php

namespace App\Http\Controllers;

use App\Models\Catalog\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        return response()
            ->view('sitemap.index')
            ->header('Content-Type', 'application/xml');
    }

    public function static()
    {
        $urls = [
            url('/'),
            url('/about-us'),
            url('/contact-us'),
            url('/policy'),
            url('/faq'),
            url('/terms-and-conditions'),
            url('/shipping-policy'),
            url('/return-and-refunds'),
            url('/clearance'),
            url('/featured'),
            url('/products'),
            url('/search'),
            url('/checkout'),
        ];

        return response()
            ->view('sitemap.static', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    public function products()
    {
        // Very low memory — loading products in chunks
        $products = Product::select('slug', 'updated_at')->chunk(100, function ($items) use (&$data) {
            foreach ($items as $p) {
                $data[] = [
                    'loc' => url('/products/' . $p->slug),
                    'lastmod' => $p->updated_at->toAtomString(),
                ];
            }
        });

        return response()
            ->view('sitemap.products', ['products' => $data ?? []])
            ->header('Content-Type', 'application/xml');
    }
}
