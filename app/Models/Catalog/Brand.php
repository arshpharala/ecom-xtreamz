<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing    = false;
    protected $keyType      = 'string';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'logo',
        'is_active',
        'position'
    ];
}
