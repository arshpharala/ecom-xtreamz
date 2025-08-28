<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'image',
        'background',
        'text_color',
        'btn_text',
        'btn_color',
        'btn_link',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function translations()
    {
        return $this->hasMany(BannerTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(BannerTranslation::class)
            ->where('locale', app()->getLocale());
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    public function scopeWithJoins($query)
    {
        return $query->leftJoin('banner_translations', 'banners.id', '=', 'banner_translations.banner_id');
    }

    public function scopeWithSelection($query)
    {
        return $query->select('banners.*', 'banner_translations.title as title', 'banner_translations.subtitle as subtitle', 'banner_translations.description as description');
    }
}
