<?php

namespace App\Models\CMS;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PaymentGateway extends Model
{
    use SoftDeletes;

    protected $fillable = ['gateway', 'key', 'secret', 'additional', 'is_active'];

    protected $casts = [
        'additional' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Encrypt/Decrypt the key field.
     */
    protected function key(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Crypt::decryptString($value) : null,
            set: fn($value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    /**
     * Encrypt/Decrypt the secret field.
     */
    protected function secret(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Crypt::decryptString($value) : null,
            set: fn($value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    function scopeActive($query)
    {
        $query->where('is_active', 1);
    }
}
