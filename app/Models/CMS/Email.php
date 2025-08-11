<?php

namespace App\Models\CMS;

use App\Models\Admin;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Email extends Model
{
    use HasUuids, SoftDeletes;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'reference',
        'template',
        'subject',
        'from_email',
        'from_name',
        'reply_to_email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function recipients()
    {
        return $this->belongsToMany(Admin::class, 'email_admin')->withPivot('type');
    }

    public function to()
    {
        return $this->belongsToMany(Admin::class, 'email_admin')->wherePivot('type', 'to');
    }

    public function cc()
    {
        return $this->belongsToMany(Admin::class, 'email_admin')->wherePivot('type', 'cc');
    }

    public function bcc()
    {
        return $this->belongsToMany(Admin::class, 'email_admin')->wherePivot('type', 'bcc');
    }

    public function exclude()
    {
        return $this->belongsToMany(Admin::class, 'email_admin')->wherePivot('type', 'exclude');
    }



}
