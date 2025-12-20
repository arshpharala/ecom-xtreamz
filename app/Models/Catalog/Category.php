<?php

namespace App\Models\Catalog;

use App\Trait\HasMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes, HasUuids, HasMeta;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'is_visible' => 'boolean',
        'show_in_menu' => 'boolean',
    ];

    protected $fillable = [
        'slug',
        'icon',
        'image',
        'banner_image',
        'parent_id',
        'position',
        'is_visible',
        'show_in_menu',
        'show_on_homepage',
        'text_color',
        'background_color',
        'reference_id', // api reference id
    ];

    function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(CategoryTranslation::class)->where('locale', app()->getLocale());
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'category_attributes');
    }

    function scopeWithJoins($query)
    {
        $query->leftJoin('category_translations', function ($join) {
            $join->on('category_translations.category_id', 'categories.id')->where('locale', app()->getLocale());
        });
    }

    function scopeWithSelection($query)
    {
        $query->select(
            'categories.id',
            'categories.slug',
            'categories.icon',
            'categories.image',
            'categories.show_on_homepage',
            'categories.is_visible',
            'categories.banner_image',
            'categories.text_color',
            'categories.background_color',
            'categories.created_at',
            'category_translations.name'
        );
    }

    public function scopeWithFilters($query, $filters)
    {
        return $query
            ->when($filters['is_new'] ?? null, fn($q, $v) => $q->where('categories.created_at', '>=', now()->subDays(30)))
            ->when($filters['show_on_homepage'] ?? null, fn($q, $v) => $q->where('categories.show_on_homepage', $v))
            ->when($filters['parent_id'] ?? null, fn($q, $v) => $q->where('categories.parent_id', $v))
            ->when($filters['search'] ?? null, fn($q, $v) => $q->where('categories.name', 'like', "%$v%"))
            ->when(
                $filters['product'] ?? null,
                fn($q) =>
                $q->whereHas('products')
            );
    }


    public function scopeApplySorting($query, $sortBy)
    {
        return match ($sortBy) {
            'newest' => $query->orderBy('categories.created_at', 'desc'),
            'oldest' => $query->orderBy('categories.created_at', 'asc'),
            default => $query->orderBy('categories.position'),
        };
    }
}
