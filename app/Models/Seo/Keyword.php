<?php

namespace App\Models\Seo;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $fillable = [
        'keyword'
    ];

    public function metas()
    {
        return $this->belongsToMany(Meta::class, 'meta_keyword');
    }
}
