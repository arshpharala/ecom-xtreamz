<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

use App\Models\Cart\OrderLineItem;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnRequestItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'return_request_id',
        'order_line_item_id',
        'quantity',
    ];

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function orderLineItem()
    {
        return $this->belongsTo(OrderLineItem::class);
    }
}
