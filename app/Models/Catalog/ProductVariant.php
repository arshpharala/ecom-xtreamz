<?php

namespace App\Models\Catalog;

use App\Models\Attachment;
use App\Models\Catalog\Product;
use App\Models\Catalog\AttributeValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes, HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['product_id', 'sku', 'price', 'stock', 'deleted_at'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function shipping()
    {
        return $this->hasOne(ProductVariantShipping::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_value');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
