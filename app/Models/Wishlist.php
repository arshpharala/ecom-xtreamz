<?php

namespace App\Models;

use Illuminate\Support\Collection;
use App\Models\Catalog\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Wishlist extends Model
{
    use HasUuids;

    public $cache;

    protected $fillable = [
        'user_id',
        'product_variant_id'
    ];

    public function scopeForUser($query, $userId)
    {
        return $query->where('wishlists.user_id', $userId);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public static function isWishlisted($userId, $variantId): bool
    {
        $cache = cache()->get("user.{$userId}.wishlists");

        $cache = $cache instanceof Collection ? $cache : self::cacheWishlists($userId);

        return $cache->contains('product_variant_id', $variantId);
    }

    public static function cacheWishlists($userId): Collection
    {
        cache()->forget("user.{$userId}.wishlists");
        return cache()->remember("user.{$userId}.wishlists", 60, function () use ($userId) {
            return Wishlist::forUser($userId)->get();
        });
    }
}
