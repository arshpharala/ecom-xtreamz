<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    
    protected $keyType = 'string';
    protected $fillable = ['name', 'slug', 'logo'];
}
