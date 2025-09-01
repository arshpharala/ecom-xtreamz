<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
        'discount_type',
        'discount_value',
        'starts_at',
        'ends_at',
        'is_active',
        'show_in_slider',
        'banner_image',
        'bg_color',
        'link_url',
        'position',
    ];

    public $appends = ['label'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'show_in_slider' => 'boolean'
    ];

    public function getLabelAttribute()
    {
        if ($this->discount_type === 'percent') {
            $value = $this->discount_value;

            $formatted = fmod($value, 1) == 0
                ? (int)$value
                : number_format($value, 2);

            return "{$formatted}% OFF";
        } elseif ($this->discount_type === 'fixed') {
            $value = $this->discount_value;

            $formatted = fmod($value, 1) == 0
                ? (int)$value
                : number_format($value, 2);

            return "{$formatted} " . active_currency() . " OFF";
        }

        return null;
    }

    public function getDiscountedPriceAttribute()
    {
        if (!$this->relationLoaded('variant')) {
            return null; // only works when variant is loaded
        }

        $variant = $this->variant;

        if ($this->discount_type === 'percent') {
            return round($variant->price * (1 - $this->discount_value / 100), 2);
        } elseif ($this->discount_type === 'fixed') {
            return max(0, $variant->price - $this->discount_value);
        }

        return $variant->price;
    }

    function translations()
    {
        return $this->hasMany(OfferTranslation::class);
    }

    function translation()
    {
        return $this->hasOne(OfferTranslation::class)->where('locale', app()->getLocale());
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'offer_product_variant');
    }

    function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>', now());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    function scopeWithJoins($query)
    {
        return $query->leftJoin('offer_translations', 'offers.id', '=', 'offer_translations.offer_id');;
    }

    function scopeWithSelection($query)
    {
        return $query->select('offers.*', 'offer_translations.title as title');
    }
}
