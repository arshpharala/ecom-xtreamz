<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class OfferTranslation extends Model
{
    protected $fillable = [
        'offer_id',
        'locale',
        'title',
        'description',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

}
