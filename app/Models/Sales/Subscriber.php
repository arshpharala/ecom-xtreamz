<?php

namespace App\Models\Sales;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'user_agent',
        'subscribed_at',
        'unsubscribed_at',
    ];

    public $casts = [
        'subscribed_at'   => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
