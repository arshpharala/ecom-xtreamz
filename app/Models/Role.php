<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'is_active'];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users associated with the role.
     */
    public function admins()
    {
        return $this->belongsToMany(User::class, 'admin_roles');
    }
}
