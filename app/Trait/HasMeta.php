<?php

namespace App\Trait;

use App\Models\Seo\Meta;

trait HasMeta
{
    public function metas()
    {
        return $this->morphMany(Meta::class, 'metable');
    }

    public function metaForLocale($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->metas()->where('locale', $locale)->first();
    }
}
