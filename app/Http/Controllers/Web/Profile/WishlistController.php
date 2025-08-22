<?php

namespace App\Http\Controllers\Web\Profile;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWishlistRequest;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWishlistRequest $request)
    {
        $userId = auth()->user()->id;

        $validated = $request->validated();

        $toggle = (bool)$validated['toggle'];

        $existing = Wishlist::query()
            ->where('user_id', $userId)
            ->when($validated['product_variant_id'], fn($q) => $q->where('product_variant_id', $validated['product_variant_id']))
            ->first();

        if ($toggle) {
            if ($existing) {
                $existing->delete();
                $message = 'Removed from wishlist';
            } else {
                Wishlist::create($validated + ['user_id' => $userId]);

                $message = 'Added to wishlist';
            }
        } elseif (!$existing) {
            Wishlist::create($validated + ['user_id' => $userId]);

            $message = 'Added to wishlist';
        } else {
            $message = 'Already in wishlist';
        }

        Wishlist::cacheWishlists($userId);

        return $this->jsonSummary(true, $message, $userId);
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
    public function destroy(string $id)
    {
        $wishlist = Wishlist::forUser(auth()->user()->id)->findOrFail($id);
        $wishlist->delete();

        return $this->jsonSummary(true, 'Removed from wishlist', auth()->user()->id);
    }

    protected function jsonSummary(bool $ok, string $message, int|string $userId)
    {
        $count = Wishlist::forUser($userId)->count();

        return response()->json([
            'success' => $ok,
            'message' => $message,
            'wishlist' => [
                'count' => $count,
            ],
        ]);
    }
}
