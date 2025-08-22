<?php

namespace App\Models\Inventory;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InventorySource extends Model
{
    use HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'description',
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_fax',
        'country_id',
        'state_id',
        'city_id',
        'street',
        'postcode',
        'lat',
        'lng',
        'priority',
        'is_active'
    ];

    public function stocks()
    {
        return $this->hasMany(InventorySourceStock::class);
    }
}
