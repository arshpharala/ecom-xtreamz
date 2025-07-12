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

    protected $fillable = ['slug', 'is_active', 'position'];

    public function translations()
    {
        return $this->hasMany(PageTranslation::class);
    }
}
