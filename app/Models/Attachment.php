<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['file_path', 'file_type', 'file_name', 'attachable_id', 'attachable_type'];

    public function attachable()
    {
        return $this->morphTo();
    }


    public function getUrlAttribute(): string
    {
        // If already a full URL (http / https), return as-is
        if (Str::startsWith($this->file_path, ['http://', 'https://'])) {
            return $this->file_path;
        }

        // Otherwise treat as local storage file
        return asset('storage/' . $this->file_path);
    }
}
