<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariantShipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'length',
        'width',
        'height',
        'weight',
    ];
}
