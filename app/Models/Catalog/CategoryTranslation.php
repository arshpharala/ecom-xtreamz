<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['category_id', 'locale', 'name', 'reference_name'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
