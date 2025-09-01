<?php

namespace App\Http\Controllers\Admin\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Offer;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductVariant;

class ProductVariantOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($productId, $variantId)
    {
        $variant            = ProductVariant::with('offers')->where('product_id', $productId)->findOrFail($variantId);
        $offers             = Offer::where(function($q){
            $q->active()->orWhere('starts_at', '>', now());
        })->withSelection()->withJoins()
            ->whereNotIn('offers.id', $variant->offers->pluck('id')->toArray())->get();

        $data['offers']     = $offers;
        $data['variant']    = $variant;

        $response['view']   = view('theme.adminlte.catalog.products.offers.index', $data)->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($variantId)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $productId, $variantId)
    {
        $variant            = ProductVariant::with('offers')->where('product_id', $productId)->findOrFail($variantId);
        $offer              = Offer::where(function($q){
            $q->active()->orWhere('starts_at', '>', now());
        })->findOrFail($request->offer_id);

        $variant->offers()->syncWithoutDetaching($offer);

        return response()->json([
            'success' => true,
            'redirect' => route('admin.catalog.products.edit', ['product' => $productId]),
            'message' => __('Offer added to product variant successfully.')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($productId, $variantId, string $id)
    {
        $variant            = ProductVariant::with('offers')->where('product_id', $productId)->findOrFail($variantId);
        $offer              = Offer::upcoming()->findOrFail($id);
        $variant->offers()->detach($offer);

        return response()->json([
            'success' => true,
            'redirect' => route('admin.catalog.products.edit', ['product' => $productId]),
            'message' => __('Offer removed from product variant successfully.')
        ]);
    }
}
