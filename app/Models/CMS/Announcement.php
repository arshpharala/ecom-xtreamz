<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'link',
        'icon',
        'is_active',
        'position',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    function scopeActive($query){
        
    }

    public function translations()
    {
        return $this->hasMany(AnnouncementTranslation::class, 'announcement_id');
    }

    public function translation()
    {
        return $this->hasOne(AnnouncementTranslation::class)->where('locale', app()->getLocale());
    }
}
