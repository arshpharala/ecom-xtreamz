<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttributeValue extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['attribute_id', 'value', 'reference_id', 'reference_value'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_attribute_value');
    }

    /**
     * Get the sorting weight for a value (specifically for sizes)
     */
    public static function getSizeSortWeight($value)
    {
        $value = strtolower(trim($value));
        $weights = [
            'one size'  => 0,
            '3xs'       => 5,
            'xxxs'      => 5,
            '2xs'       => 10,
            'xxs'       => 10,
            'xs'        => 15,
            'extra small' => 15,
            's'         => 20,
            'small'     => 20,
            'm'         => 30,
            'medium'    => 30,
            'l'         => 40,
            'large'     => 40,
            'xl'        => 50,
            'extra large' => 50,
            'xxl'       => 60,
            '2xl'       => 60,
            'xxxl'      => 70,
            '3xl'       => 70,
            'xxxxl'     => 80,
            '4xl'       => 80,
            '5xl'       => 90,
            '6xl'       => 100,
        ];

        return $weights[$value] ?? 999;
    }
}
