<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;

class Packaging extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'reference_id',
        'reference_name',
    ];
}
