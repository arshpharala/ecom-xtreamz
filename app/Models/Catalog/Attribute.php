<?php

namespace App\Models\Catalog;

use App\Models\Catalog\AttributeValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends Model
{
    use HasFactory, SoftDeletes, HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['name'];

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
