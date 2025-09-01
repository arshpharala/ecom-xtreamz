<?php

namespace App\Models\CMS;

use App\Models\Catalog\Category;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'category_id',
        'is_guide',
        'position',
        'is_active',
        'author',
        'published_at',
        'thumbnail',
        'image'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_guide' => 'boolean',
    ];

    public function category()
    {
        return $this->belongTo(Category::class);
    }

    public function translations()
    {
        return $this->hasMany(TestimonialTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(TestimonialTranslation::class)->where('locale', app()->getLocale());
    }
}
