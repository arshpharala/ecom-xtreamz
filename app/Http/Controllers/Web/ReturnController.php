<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Cart\Order;
use App\Models\Sales\ReturnRequest;
use App\Models\Sales\ReturnRequestItem;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Attachment;
use App\Notifications\ReturnRequestCreated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    /**
     * Load items for an eligible order for the return form.
     */
    public function getOrderItems(Order $order)
    {
        // Eligibility is encapsulated by Order::canBeReturned()
        $ownedByUser = $order->user_id === (\Auth::user()?->id ?? null);

        if (! $ownedByUser || ! $order->canBeReturned()) {
            return response()->json(['error' => 'Not eligible'], 403);
        }

        $items = $order->lineItems()->with('productVariant.product')->get();

        return view('theme.xtremez.components.profile._return_items_list', compact('items'))->render();
    }

    /**
     * Store a new return request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'return_reason_id' => 'required|exists:return_reasons,id',
            'items' => 'required|array',
            'items.*.order_line_item_id' => 'required|exists:order_line_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'refund_method' => 'required|in:original_method,account_credits',
            'attachments.*' => 'nullable|image|max:2048'
        ]);

        $order = Order::findOrFail($request->order_id);

        // Enforce eligibility via Order::canBeReturned()
        $ownedByUser = $order->user_id === (\Auth::user()?->id ?? null);

        if (! $ownedByUser || ! $order->canBeReturned()) {
            return response()->json(['message' => 'This order is not eligible for return.'], 403);
        }

        return DB::transaction(function () use ($request, $order) {
            $user = \Auth::user();
            $returnRequest = ReturnRequest::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'return_reason_id' => $request->return_reason_id,
                'description' => $request->description,
                'refund_method' => $request->refund_method,
                'status' => ReturnRequest::STATUS_REQUESTED,
                'refund_status' => 'pending',
            ]);

            foreach ($request->items as $itemData) {
                // Verify quantity logic (should not exceed purchased - already returned)
                $lineItem = $order->lineItems()->findOrFail($itemData['order_line_item_id']);
                $returnableQty = $lineItem->getReturnableQuantity();

                if ($itemData['quantity'] > $returnableQty) {
                    throw new \Exception("Quantity for {$lineItem->productVariant->product->translation->name} exceeds returnable amount.");
                }

                ReturnRequestItem::create([
                    'return_request_id' => $returnRequest->id,
                    'order_line_item_id' => $lineItem->id,
                    'quantity' => $itemData['quantity'],
                ]);
            }

            // Handle attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('returns', 'public');

                    $returnRequest->attachments()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            // Record Initial Timeline
            $returnRequest->timelines()->create([
                'actor_type' => 'user',
                'actor_id' => $user->id,
                'title' => 'Return Request Submitted',
                'new_status' => ReturnRequest::STATUS_REQUESTED,
                'remarks' => 'Customer submitted a return request for ' . $returnRequest->items->sum('quantity') . ' item(s).',
            ]);

            // Notify Customer
            $user->notify(new ReturnRequestCreated($returnRequest, 'customer'));

            // Notify Sales Team
            Notification::route('mail', 'sales@xtremez.store')
                ->notify(new ReturnRequestCreated($returnRequest, 'sales'));

            return response()->json([
                'message' => 'Return request submitted successfully!',
                'redirect' => route('customers.orders.show', $order->id)
            ]);
        });
    }
}
