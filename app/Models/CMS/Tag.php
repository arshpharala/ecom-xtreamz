<?php

namespace App\Models\CMS;

use App\Models\Catalog\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tag extends Model
{
    use HasUuids, SoftDeletes;

    public $incrementing    = false;
    protected $keyType      = 'string';

    protected $fillable = [
        'name',
        'position',
        'is_active',
        'reference_id',
        'reference_name'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    function scopeActive($query)
    {
        $query->where('is_active', 1);
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'tag_product_variant');
    }
}
