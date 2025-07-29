<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Cart\Order;
use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use App\Models\Cart\OrderLineItem;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    function dashboard()
    {

        return view('theme.adminlte.dashboard');
    }

    public function metrics(Request $request)
    {
        $ordersQuery = Order::query();
        $this->applyDateFilters($ordersQuery, $request);

        $salesTotal = (clone $ordersQuery)->sum('total');
        $ordersCount = (clone $ordersQuery)->count();

        $usersQuery = User::query();
        $this->applyDateFilters($usersQuery, $request);

        $newCustomers = (clone $usersQuery)->count();

        return response()->json([
            'total_sales'     => (float) $salesTotal,
            'total_orders'    => $ordersCount,
            'total_customers' => $newCustomers,
            'total_products'  => Product::count(), // products not filtered by date
        ]);
    }


    public function salesChart(Request $request)
    {
        $query = Order::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date');

        $this->applyDateFilters($query, $request);

        $sales = $query->get()->keyBy('date');

        $start = $request->input('from') ? now()->parse($request->input('from')) : now()->subDays(6);
        $end   = $request->input('to') ? now()->parse($request->input('to')) : now();

        $data = [];
        $labels = [];

        $period = \Carbon\CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            $d = $date->format('Y-m-d');
            $labels[] = $d;
            $data[] = $sales[$d]->total ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'data'   => $data
        ]);
    }


    public function recentOrders(Request $request)
    {
        $query = Order::with('user')->latest()->take(6);
        $this->applyDateFilters($query, $request);

        $orders = $query->get()->map(function ($order) {
            return [
                'reference_number'  => $order->reference_number,
                'customer'          => $order->user->name ?? $order->email,
                'total'             => $order->total,
                'currency'          => active_currency(),
                'payment_status'    => ucfirst($order->payment_status),
                'date'              => $order->created_at->format('d M Y h:i A'),
            ];
        });

        return response()->json($orders);
    }


    public function topProducts(Request $request)
    {
        $query = OrderLineItem::selectRaw('product_variant_id, SUM(quantity) as total_qty')
            ->groupBy('product_variant_id')
            ->orderByDesc('total_qty')
            ->take(6)
            ->with('productVariant.product.translation');

        $this->applyDateFilters($query, $request);

        $items = $query->get()->map(function ($item) {
            return [
                'label' => optional($item->productVariant->product->translation)->name ?? 'Unnamed',
                'qty'   => $item->total_qty
            ];
        });

        return response()->json($items);
    }


    public function newCustomers(Request $request)
    {
        $query = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date');

        $this->applyDateFilters($query, $request);

        return response()->json($query->get());
    }

    public function paymentMethodBreakdown(Request $request)
    {
        $query = Order::selectRaw('payment_method, COUNT(*) as count')->groupBy('payment_method');
        $this->applyDateFilters($query, $request);

        return response()->json($query->get());
    }


    protected function applyDateFilters($query, Request $request, $column = 'created_at')
    {
        if ($request->filled('from')) {
            $query->whereDate($column, '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate($column, '<=', $request->input('to'));
        }

        return $query;
    }
}
