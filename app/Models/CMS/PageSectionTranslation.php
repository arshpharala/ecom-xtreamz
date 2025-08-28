<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;

class PageSectionTranslation extends Model
{
    protected $fillable = ['page_section_id', 'locale', 'heading', 'content'];
}
