<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'image',
        'company_logo',
        'company_name',
        'designation',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function translations()
    {
        return $this->hasMany(TestimonialTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(TestimonialTranslation::class)->where('locale', app()->getLocale());
    }
}
