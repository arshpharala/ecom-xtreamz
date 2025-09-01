<?php

namespace App\Models\CMS;

use App\Models\CMS\PageTranslation;
use App\Trait\HasMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory, HasUuids, SoftDeletes, HasMeta;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['slug', 'is_active', 'position', 'banner'];

    public function scopeActive($query)
    {
        return $query->where('pages.is_active', true);
    }

    public function translation()
    {
        return $this->hasOne(PageTranslation::class)->where('locale', app()->getLocale());
    }

    public function translations()
    {
        return $this->hasMany(PageTranslation::class);
    }

    public function sections()
    {
        return $this->hasMany(PageSection::class);
    }

    function scopeFindBySlug($query, $slug)
    {
        $query->where('slug', $slug);
    }

    public function scopeWithJoins($query)
    {
        $locale = app()->getLocale();

        return $query
            ->leftJoin('page_translations', function ($join) use ($locale) {
                $join->on('page_translations.page_id', '=', 'pages.id')
                    ->where('page_translations.locale', $locale);
            })
            ->leftJoin('metas', function ($join) use ($locale) {
                $join->on('metas.metable_id', '=', 'pages.id')
                    ->where('metas.metable_type', '=', Page::class)
                    ->where('metas.locale', $locale);
            });
    }


    public function scopeWithSelection($query)
    {
        return $query->select([
            'pages.id',
            'page_translations.title',
            'page_translations.content',
            'metas.meta_description',
        ]);
    }
}
