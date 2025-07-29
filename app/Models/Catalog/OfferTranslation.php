<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OfferTranslation extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;


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
