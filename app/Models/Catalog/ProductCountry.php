<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductCountry extends Model
{
    protected $fillable = ['product_id', 'country_code', 'currency_code', 'price', 'is_available', 'tax'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

