<?php

namespace App\Models\CMS;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Models\CMS\PageSectionTranslation;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSection extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'page_id',
        'type',
        'image',
        'settings',
        'position',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function translation()
    {
        return $this->hasOne(PageSectionTranslation::class)->where('locale', app()->getLocale());
    }

    public function translations()
    {
        return $this->hasMany(PageSectionTranslation::class);
    }


    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
