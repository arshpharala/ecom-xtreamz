<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Attachment;
use App\Models\Cart\Order;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_number',
        'order_id',
        'user_id',
        'return_reason_id',
        'description',
        'status',
        'shipping_cost_borne_by',
        'refund_method',
        'refund_status',
        'admin_notes',
        'approved_at',
        'shipped_at',
        'received_at',
        'refunded_at',
        'shipping_label_path',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'shipped_at' => 'datetime',
        'received_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($returnRequest) {
            if (empty($returnRequest->reference_number)) {
                $returnRequest->reference_number = static::generateReferenceNumber();
            }
        });
    }

    public static function generateReferenceNumber(): string
    {
        $prefix = 'RET';

        $lastReturn = static::withTrashed()
            ->where('reference_number', 'like', "$prefix-%")
            ->orderByDesc('reference_number')
            ->first();

        $nextNumber = 1;

        if ($lastReturn && preg_match('/RET-(\d+)$/', $lastReturn->reference_number, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }

        return $prefix . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function reason()
    {
        return $this->belongsTo(ReturnReason::class, 'return_reason_id');
    }

    public function items()
    {
        return $this->hasMany(ReturnRequestItem::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
