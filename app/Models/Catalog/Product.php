<?php

namespace App\Models\Catalog;

use App\Trait\HasMeta;
use Illuminate\Support\Arr;
use App\Models\Catalog\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasUuids, HasMeta;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'category_id',
        'brand_id',
        'slug',
        'is_active',
        'is_featured',
        'is_new',
        'show_in_slider',
        'position',
        'reference_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    function categories()
    {
        return $this->belongsToMany(Category::class, 'category_products');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(ProductTranslation::class)->where('locale', app()->getLocale());
    }


    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attachments()
    {
        return $this->morphMany(\App\Models\Attachment::class, 'attachable');
    }

    // For multi-country prices
    public function countries()
    {
        return $this->hasMany(ProductCountry::class);
    }

    public function scopeWithJoins($query)
    {
        $query->leftJoin('categories', 'categories.id', 'products.category_id')
            ->leftJoin('product_translations', function ($join) {
                $join->on('products.id', 'product_translations.product_id')->where('product_translations.locale', app()->getLocale());
            })
            ->leftJoin('category_translations', function ($join) {
                $join->on('categories.id', 'category_translations.category_id')->where('category_translations.locale', app()->getLocale());
            })
            ->leftJoin('brands', 'brands.id', 'products.brand_id');
    }

    public function scopeWithSelection($query)
    {
        $query->select(
            'products.id',
            'products.category_id',
            'products.brand_id',
            'products.slug',
            'products.is_active',
            'products.is_featured',
            'products.is_new',
            'products.show_in_slider',
            'products.position',
            'products.created_at',
            'products.deleted_at',
            'product_translations.name as name',
            'category_translations.name as category_name',
            'brands.name as brand_name'
        );
    }

    public function scopeWithFilters($query, $filters)
    {
        $query->when($filters['status'] ?? null, function ($q, $status) {
            if ($status === 'active') {
                $q->where('products.is_active', 1)->whereNull('products.deleted_at');
            } elseif ($status === 'inactive') {
                $q->where('products.is_active', 0)->whereNull('products.deleted_at');
            } elseif ($status === 'deleted') {
                $q->whereNotNull('products.deleted_at');
            }
        })
            ->when($filters['category_id'] ?? null, function ($q, $categoryId) {
                $q->whereIn('products.category_id', Arr::wrap($categoryId));
            })
            ->when($filters['brand_id'] ?? null, function ($q, $brandId) {
                $q->whereIn('products.brand_id', Arr::wrap($brandId));
            })
            ->when(isset($filters['is_featured']) ? $filters['is_featured'] : null, function ($q, $isFeatured) {
                $q->where('products.is_featured', $isFeatured);
            })
            ->when($filters['is_new'] ?? null, fn($q, $v) => $q->where('products.created_at', '>=', now()->subDays(30)))
            ->when(isset($filters['show_in_slider']) ? $filters['show_in_slider'] : null, function ($q, $showInSlider) {
                $q->where('products.show_in_slider', $showInSlider);
            });
    }

    public function scopeApplySorting($query, $sortBy)
    {
        return match ($sortBy) {
            'newest' => $query->orderBy('products.created_at', 'desc'),
            default => $query->orderBy('products.position'),
        };
    }
}
