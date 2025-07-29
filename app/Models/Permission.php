<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'module_id',
        'name',
        'is_active',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
