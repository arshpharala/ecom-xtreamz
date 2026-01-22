<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Attachment;
use App\Models\Cart\Order;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ReturnRequest extends Model
{
    use SoftDeletes;

    // Return States
    const STATUS_REQUESTED   = 'requested';
    const STATUS_ACCEPTED    = 'accepted';
    const STATUS_IN_TRANSIT  = 'in_transit';
    const STATUS_RECEIVED    = 'received';
    const STATUS_INSPECTION  = 'under_inspection';
    const STATUS_RESOLVING   = 'resolving';
    const STATUS_COMPLETED   = 'completed';
    const STATUS_REJECTED    = 'rejected';

    // Inspection Statuses
    const INSPECTION_PENDING = 'pending';
    const INSPECTION_PASSED  = 'passed';
    const INSPECTION_FAILED  = 'failed';

    // Resolution Types
    const RESOLUTION_REFUND       = 'refund';
    const RESOLUTION_REPLACEMENT  = 'replacement';
    const RESOLUTION_STORE_CREDIT = 'store_credit';

    protected $fillable = [
        'reference_number',
        'order_id',
        'user_id',
        'return_reason_id',
        'reason_category',
        'description',
        'status',
        'shipping_cost_borne_by',
        'refund_method',
        'refund_status',
        'refund_reference',
        'resolution_type',
        'customer_tracking_number',
        'carrier_name',
        'inspection_status',
        'inspection_notes',
        'replacement_order_id',
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

    public function timelines()
    {
        return $this->hasMany(ReturnRequestTimeline::class)->latest();
    }

    /**
     * Transition the return request to a new state.
     */
    public function transitionTo(string $newStatus, ?string $remarks = null, $actor = null)
    {
        $oldStatus = $this->status;
        if ($oldStatus === $newStatus && empty($remarks)) {
            return;
        }

        $this->update(['status' => $newStatus]);

        // Auto-update timestamps
        $timestampMap = [
            self::STATUS_ACCEPTED   => 'approved_at',
            self::STATUS_IN_TRANSIT => 'shipped_at',
            self::STATUS_RECEIVED   => 'received_at',
            self::STATUS_COMPLETED  => 'refunded_at',
        ];

        if (isset($timestampMap[$newStatus]) && !$this->{$timestampMap[$newStatus]}) {
            $this->update([$timestampMap[$newStatus] => now()]);
        }

        // Record Timeline
        $actorType = 'system';
        $actorId = null;

        if ($actor) {
            $actorType = get_class($actor);
            $actorId = $actor->id;
        } elseif ($admin = Auth::guard('admin')->user()) {
            $actorType = get_class($admin);
            $actorId = $admin->id;
        } elseif ($user = Auth::user()) {
            $actorType = get_class($user);
            $actorId = $user->id;
        }

        return $this->timelines()->create([
            'actor_type' => $actorType,
            'actor_id'   => $actorId,
            'title'      => 'Status updated to ' . strtoupper(str_replace('_', ' ', $newStatus)),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'remarks'    => $remarks,
        ]);
    }
}
