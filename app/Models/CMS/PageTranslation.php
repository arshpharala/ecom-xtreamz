<?php

namespace App\Models\CMS;

use App\Trait\HasMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['page_id', 'locale', 'title', 'content'];

    function page(){
        return $this->belongsTo(Page::class);
    }
}
