<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['product_id', 'locale', 'name', 'description'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
