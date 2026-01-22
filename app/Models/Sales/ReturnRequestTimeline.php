<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
use App\Models\User;

class ReturnRequestTimeline extends Model
{
    protected $fillable = [
        'return_request_id',
        'actor_type',
        'actor_id',
        'title',
        'old_status',
        'new_status',
        'remarks',
    ];

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function actor()
    {
        return $this->morphTo();
    }

    /**
     * Get the actor's name based on actor_type.
     */
    public function getActorNameAttribute()
    {
        // Handle literal strings from legacy records or system
        if ($this->actor_type === 'system') {
            return 'System';
        }

        if ($this->actor_type === 'admin') {
            return 'Administrator';
        }

        if ($this->actor_type === 'user') {
            return 'Customer';
        }

        // Handle Laravel Class Names (New standard)
        if ($this->actor) {
            return $this->actor->name;
        }

        // Fallback for identified types without a loaded relationship
        if (str_contains($this->actor_type, 'Admin')) return 'Administrator';
        if (str_contains($this->actor_type, 'User')) return 'Customer';

        return 'Unknown';
    }
}
