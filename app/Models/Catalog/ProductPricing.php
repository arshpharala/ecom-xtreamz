<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductPricing extends Model
{
    protected $fillable = [
        'product_variant_id',
        'country_id',
        'currency_id',
        'price',
        'cost_price',
        'wholesale_price',
    ];
}
