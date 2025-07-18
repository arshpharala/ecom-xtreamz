<?php

namespace App\Models\Catalog;

use App\Models\Attachment;
use App\Models\Catalog\Product;
use App\Models\Catalog\AttributeValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes, HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['product_id', 'sku', 'price', 'stock', 'deleted_at'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function shipping()
    {
        return $this->hasOne(ProductVariantShipping::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_value');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function scopeWithJoins($query)
    {
        $locale = app()->getLocale();

        return $query
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('product_translations', function ($join) use ($locale) {
                $join->on('product_translations.product_id', '=', 'products.id')
                    ->where('product_translations.locale', $locale);
            })
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('category_translations', function ($join) use ($locale) {
                $join->on('categories.id', '=', 'category_translations.category_id')
                    ->where('category_translations.locale', $locale);
            })
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->leftJoin('attachments as main_attachment', function ($join) {
                $join->on('main_attachment.attachable_id', '=', 'product_variants.id')
                    ->where('main_attachment.attachable_type', ProductVariant::class)
                    ->whereRaw('ec_main_attachment.id = (
                    SELECT a.id FROM ec_attachments a
                    WHERE a.attachable_id = ec_product_variants.id
                      AND a.attachable_type = "' . addslashes(ProductVariant::class) . '"
                    ORDER BY a.created_at ASC LIMIT 1
                )');
            });
    }

    public function scopeWithSelection($query)
    {
        return $query->select([
            'products.id as product_id',
            'products.slug',
            'products.category_id',
            'products.position',
            'product_variants.id as variant_id',
            'product_variants.price',
            'product_variants.stock',
            'product_translations.name',
            'product_translations.description',
            'brands.name as brand_name',
            'category_translations.name as category_name',
            'main_attachment.file_path',
            'main_attachment.file_name'
        ]);
    }

    public function scopeWithFilters($query, $filters)
    {
        return $query
            ->when($filters['is_featured'] ?? null, fn($q, $v) => $q->where('products.is_featured', $v))
            ->when($filters['show_in_slider'] ?? null, fn($q, $v) => $q->where('products.show_in_slider', $v))
            ->when($filters['category_id'] ?? null, fn($q, $v) => $q->where('products.category_id', $v))
            ->when($filters['brand_id'] ?? null, fn($q, $v) => $q->where('products.brand_id', $v))
            ->when($filters['price_min'] ?? null, fn($q, $v) => $q->where('product_variants.price', '>=', $v))
            ->when($filters['price_max'] ?? null, fn($q, $v) => $q->where('product_variants.price', '<=', $v))
            ->when($filters['search'] ?? null, fn($q, $v) => $q->where('product_translations.name', 'like', "%$v%"))
            ->when(!empty($filters['attributes']), fn($q) => $q->filterByAttributes($filters['attributes']));
    }

    public function scopeFilterByAttributes($query, $attributes)
    {
        foreach ($attributes as $attributeId => $valueId) {
            if ($valueId) {
                $query->whereHas('attributeValues', function ($q) use ($attributeId, $valueId) {
                    $q->where('attribute_id', $attributeId)
                        ->where('attribute_values.id', $valueId);
                });
            }
        }
        return $query;
    }

    public function scopeApplySorting($query, $sortBy)
    {
        return match ($sortBy) {
            'price_asc' => $query->orderBy('product_variants.price', 'asc'),
            'price_desc' => $query->orderBy('product_variants.price', 'desc'),
            'newest' => $query->orderBy('products.created_at', 'desc'),
            default => $query->orderBy('products.position'),
        };
    }
}
