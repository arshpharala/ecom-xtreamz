<?php

namespace App\Http\Controllers\Admin\Sales;

use Illuminate\Http\Request;
use App\Models\Sales\ReturnRequest;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\ReturnRequestUpdated;

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
                    $viewUrl = route('admin.sales.return-requests.show', $row->id);
                    $deleteUrl = route('admin.sales.return-requests.destroy', $row->id);
                    $restoreUrl = route('admin.sales.return-requests.restore', $row->id);
                    return view('theme.adminlte.components._table-actions', [
                        'row' => $row,
                        'editSidebar' => false,
                        'editUrl' => $viewUrl,
                        'deleteUrl' => $deleteUrl,
                        'restoreUrl' => $restoreUrl,
                    ])->render();
                })
                ->editColumn('created_at', fn($row) => $row->created_at?->format('d-M-Y h:i A'))
                ->editColumn('status', function($row) {
                    $badges = [
                        'pending'  => 'badge-warning',
                        'approved' => 'badge-info',
                        'rejected' => 'badge-danger',
                        'shipped'  => 'badge-primary',
                        'received' => 'badge-purple',
                        'refunded' => 'badge-success',
                    ];
                    $badgeClass = $badges[$row->status] ?? 'badge-secondary';
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('user_name', fn($row) => $row->user?->name)
                ->addColumn('order_ref', fn($row) => $row->order?->reference_number)
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
            'attachments'
        ])->findOrFail($id);

        return view('theme.adminlte.sales.return-requests.show', compact('returnRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $returnRequest = ReturnRequest::findOrFail($id);
        
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected,shipped,received,refunded',
            'shipping_cost_borne_by' => 'nullable|in:company,customer',
            'refund_status' => 'nullable|in:pending,completed',
            'admin_notes' => 'nullable|string',
            'shipping_label' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('shipping_label')) {
            $data['shipping_label_path'] = $request->file('shipping_label')->store('return_labels', 'public');
        }

        $returnRequest->update($data);

        // Update timestamps based on status
        if ($data['status'] === 'approved' && !$returnRequest->approved_at) {
            $returnRequest->update(['approved_at' => now()]);
        } elseif ($data['status'] === 'shipped' && !$returnRequest->shipped_at) {
            $returnRequest->update(['shipped_at' => now()]);
        } elseif ($data['status'] === 'received' && !$returnRequest->received_at) {
            $returnRequest->update(['received_at' => now()]);
            
            // Re-stock items
            foreach ($returnRequest->items as $item) {
                $variant = $item->orderLineItem->productVariant;
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                }
            }
        } elseif ($data['status'] === 'refunded' && !$returnRequest->refunded_at) {
            $returnRequest->update(['refunded_at' => now(), 'refund_status' => 'completed']);
        }

        // Notify Customer (CC's Sales internally)
        $returnRequest->user->notify(new ReturnRequestUpdated($returnRequest));

        return response()->json(['message' => 'Return Request updated.']);
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
