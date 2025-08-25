<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;

class TestimonialTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'testimonial_id',
        'locale',
        'name',
        'description'
    ];

    function testimonial()
    {
        return $this->belongsTo(Testimonial::class);
    }
}
