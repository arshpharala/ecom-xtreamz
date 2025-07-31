<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['code', 'name', 'currency_id', 'icon'];

    function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
