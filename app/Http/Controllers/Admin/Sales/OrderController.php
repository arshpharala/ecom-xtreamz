<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Models\Cart\Order;
use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::withTrashed()->withJoins()
                ->select(
                    'orders.id',
                    'orders.reference_number',
                    'orders.total',
                    'orders.payment_status',
                    'orders.created_at',
                    'orders.deleted_at',
                    'users.name as user_name',
                    'users.email as user_email',
                    'currencies.code as currency_code'
                )
                ->groupBy('orders.id');

            return DataTables::of($orders)
                ->addColumn('action', function ($row) {
                    $viewUrl = route('admin.sales.orders.show', $row->id);
                    $deleteUrl = route('admin.sales.orders.destroy', $row->id);
                    $restoreUrl = route('admin.sales.orders.restore', $row->id);
                    return view('theme.adminlte.components._table-actions', [
                        'row' => $row,
                        'editSidebar' => false,
                        'editUrl' => $viewUrl,
                        'deleteUrl' => $deleteUrl,
                        'restoreUrl' => $restoreUrl,
                    ])->render();
                })
                ->editColumn('created_at', fn($row) => $row->created_at?->format('d-M-Y h:i A'))
                ->addColumn('status', fn($row) => $row->payment_status === 'paid'
                    ? '<span class="badge badge-success">Paid</span>'
                    : '<span class="badge badge-warning">' . ucfirst($row->payment_status) . '</span>')
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('theme.adminlte.sales.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with([
            'billingAddress',
            'lineItems.productVariant.attributeValues.attribute',
            'lineItems.productVariant.product'
        ])->findOrFail($id);


        EmailService::request_mail('order-success-notification', [
            'order' => $order
        ]);


        return view('theme.adminlte.sales.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        
        $data = $request->validate([
            'delivered_at' => 'nullable|date',
            'payment_status' => 'nullable|string',
        ]);

        $order->update($data);

        if ($request->ajax()) {
            return response()->json(['message' => 'Order updated successfully.']);
        }

        return redirect()->back()->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return response()->json(['message' => 'Order deleted.']);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        Order::withTrashed()->findOrFail($id)->restore();
        return response()->json(['message' => 'Order restored.']);
    }
}
