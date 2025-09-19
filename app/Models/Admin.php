<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    function roles(){
        return $this->belongsToMany(Role::class, 'admin_roles');
    }


    public function has_permission($moduleName, $access)
    {

        $permissions    = $this->roles->load('permissions.module')->pluck('permissions')->collapse();
        $modules        = Module::all();
        $module         = $modules->where('name', $moduleName)->first();
        $permission     = $permissions->where('module_od', $module->id)->where('name', $access)->first();

        return $permission;

    }
}
