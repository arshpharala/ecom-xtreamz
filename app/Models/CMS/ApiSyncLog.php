<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ApiSyncLog extends Model
{
    use HasUuids;

    protected $fillable = [
        'source',
        'endpoint',
        'url',
        'total_records',
        'success',
        'http_status',
        'message',
        'fetched_at',
    ];

    public $casts = [
        'fetched_at' => 'datetime'
    ];
}
