<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class InventorySourceStock extends Model
{
    use HasUuids;

    protected $fillable = ['inventory_source_id', 'product_variant_id', 'qty'];

    public function source()
    {
        return $this->belongsTo(InventorySource::class, 'inventory_source_id');
    }
}
