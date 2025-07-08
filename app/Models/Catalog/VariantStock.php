<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class VariantStock extends Model
{
    protected $fillable = ['product_variant_id', 'quantity'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
