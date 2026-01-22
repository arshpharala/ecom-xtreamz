<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use App\Models\Cart\Order;
use App\Models\Sales\ReturnRequest;
use App\Notifications\ReturnRequestUpdated;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReturnRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $requests = ReturnRequest::with(['user', 'order', 'reason'])->latest();

            return DataTables::of($requests)
                ->addColumn('action', function ($row) {
                    $showUrl = route('admin.sales.return-requests.show', $row->id);

                    // $deleteUrl = route('admin.sales.return-requests.destroy', $row->id);
                    // $restoreUrl = route('admin.sales.return-requests.restore', $row->id);
                    return view('theme.adminlte.components._table-actions', [
                        'row' => $row,
                        'editSidebar' => false,
                        'showUrl' => $showUrl,
                        // 'deleteUrl' => $deleteUrl,
                        // 'restoreUrl' => $restoreUrl,
                    ])->render();
                })
                ->editColumn('created_at', fn ($row) => $row->created_at?->format('d-M-Y h:i A'))
                ->editColumn('status', function ($row) {
                    $badges = [
                        'pending' => 'badge-warning',
                        'approved' => 'badge-info',
                        'rejected' => 'badge-danger',
                        'shipped' => 'badge-primary',
                        'received' => 'badge-purple',
                        'refunded' => 'badge-success',
                    ];
                    $badgeClass = $badges[$row->status] ?? 'badge-secondary';

                    return '<span class="badge '.$badgeClass.'">'.ucfirst($row->status).'</span>';
                })
                ->addColumn('user_name', fn ($row) => $row->user?->name)
                ->addColumn('order_ref', fn ($row) => $row->order?->reference_number)
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('theme.adminlte.sales.return-requests.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $returnRequest = ReturnRequest::with([
            'user',
            'order.lineItems.productVariant.product',
            'reason',
            'items.orderLineItem.productVariant.product',
            'attachments',
        ])->findOrFail($id);

        return view('theme.adminlte.sales.return-requests.show', compact('returnRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $returnRequest = ReturnRequest::findOrFail($id);
        $actionType = $request->input('action_type', 'update');
        $admin = auth('admin')->user();
        // 1. Separate Remarks from Permanent Notes
        $remarks = $request->admin_notes; // This goes to the timeline logic
        
        // 2. Handle Attribute Updates FIRST
        // valid attributes to update on the model
        $attributesToUpdate = $request->only([
            'refund_reference',
            'refund_status',
            'resolution_type',
            'inspection_status',
            'inspection_notes',
            'shipping_label_path' // if handled here
        ]);

        // Only update admin_notes on the model if we are NOT performing a state transition
        // or if explicitly saving details without a transition.
        // Update admin_notes if provided (always used for Rejection/Timeline remarks)
        if ($request->has('admin_notes')) {
             $attributesToUpdate['admin_notes'] = $request->admin_notes;
        }

        $returnRequest->update($attributesToUpdate);

        // 3. Handle Status Transitions
        if ($actionType !== 'update') {
            $statusMap = [
                'accept'           => ReturnRequest::STATUS_ACCEPTED,
                'reject'           => ReturnRequest::STATUS_REJECTED,
                'mark_shipped'     => ReturnRequest::STATUS_IN_TRANSIT,
                'mark_received'    => ReturnRequest::STATUS_RECEIVED,
                'start_inspection' => ReturnRequest::STATUS_INSPECTION,
                'record_inspection'=> ReturnRequest::STATUS_RESOLVING,
                'complete'         => ReturnRequest::STATUS_COMPLETED,
            ];

            if (isset($statusMap[$actionType])) {
                $newStatus = $statusMap[$actionType];
                
                // Only proceed if status is actually changing (Idempotency)
                if ($returnRequest->status !== $newStatus) {
                    // For inspection, we use inspection_notes as remarks for the timeline
                    if ($actionType === 'record_inspection') {
                        $remarks = "Inspection Outcome: " . strtoupper($request->inspection_status) . ". " . $request->inspection_notes;
                    }

                    // Specific case for completion and replacement
                    if ($actionType === 'complete' && $returnRequest->resolution_type === ReturnRequest::RESOLUTION_REPLACEMENT) {
                        $newOrder = DB::transaction(function() use ($returnRequest) {
                            $originalOrder = $returnRequest->order;
                            $replacementOrder = Order::create([
                                'user_id'            => $originalOrder->user_id,
                                'billing_address_id' => $originalOrder->billing_address_id,
                                'email'              => $originalOrder->email,
                                'payment_method'     => 'replacement',
                                'payment_status'     => 'paid',
                                'status'             => 'processing',
                                'currency_id'        => $originalOrder->currency_id,
                                'sub_total'          => 0,
                                'tax'                => 0,
                                'total'              => 0,
                                'external_reference' => 'REPLACEMENT FOR ' . $returnRequest->reference_number,
                            ]);

                            $total = 0;
                            foreach ($returnRequest->items as $ritem) {
                                $itemTotal = $ritem->quantity * $ritem->orderLineItem->price;
                                $replacementOrder->lineItems()->create([
                                    'product_variant_id' => $ritem->orderLineItem->product_variant_id,
                                    'quantity'           => $ritem->quantity,
                                    'price'              => $ritem->orderLineItem->price,
                                    'subtotal'           => $itemTotal,
                                ]);
                                $total += $itemTotal;
                            }

                            $replacementOrder->update([
                                'sub_total' => $total,
                                'total'     => $total,
                            ]);

                            return $replacementOrder;
                        });

                        $returnRequest->update(['replacement_order_id' => $newOrder->id]);
                        $remarks = ($remarks ? $remarks . ". " : "") . "Replacement order #" . $newOrder->reference_number . " created.";
                    }

                    // Handle re-stocking if received
                    if ($actionType === 'mark_received') {
                        foreach ($returnRequest->items as $item) {
                            $variant = $item->orderLineItem->productVariant;
                            if ($variant) {
                                $variant->increment('stock', $item->quantity);
                            }
                        }
                    }

                    $returnRequest->transitionTo($newStatus, $remarks, $admin);
                }
            }
        }

        // Handle shipping label if provided
        if ($request->hasFile('shipping_label')) {
            $path = $request->file('shipping_label')->store('return_labels', 'public');
            $returnRequest->update(['shipping_label_path' => $path]);
        }

        // 3. Notify Customer
        try {
            $returnRequest->user->notify(new ReturnRequestUpdated($returnRequest));
        } catch (\Exception $e) {
            // Silently fail if notification fails
        }

        return response()->json([
            'message' => 'Return Request updated successfully.',
            'status'  => $returnRequest->status,
            'redirect' => route('admin.sales.return-requests.show', $returnRequest->id)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ReturnRequest::findOrFail($id)->delete();

        return response()->json(['message' => 'Return Request deleted.']);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        ReturnRequest::withTrashed()->findOrFail($id)->restore();

        return response()->json(['message' => 'Return Request restored.']);
    }
}
