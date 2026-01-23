<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'product_variant_id',
        'blocked_qty',
        'net_available_qty',
        'incoming_qty',
        'total_qty',
        'incoming_date',
    ];

    /**
     * Get the variant that owns this inventory record.
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
