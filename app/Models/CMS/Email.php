<?php

namespace App\Models\CMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    use HasUuids, SoftDeletes;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'reference',
        'template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'email_user');
    }

    public function to(){
        return $this->belongsToMany(User::class, 'email_user')->wherePivot('type', 'to');
    }

    public function cc(){
        return $this->belongsToMany(User::class, 'email_user')->wherePivot('type', 'cc');
    }

    public function bcc(){
        return $this->belongsToMany(User::class, 'email_user')->wherePivot('type', 'bcc');
    }

    public function exclude(){
        return $this->belongsToMany(User::class, 'email_user')->wherePivot('type', 'exclude');
    }
}
