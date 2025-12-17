<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPackaging extends Model
{
    protected $fillable = [
        'product_variant_id',
        'packaging_id',
        'value',
    ];
}
