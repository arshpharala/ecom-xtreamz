<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = User::withCount('orders')
                ->withSum('orders as total_spent', 'total');

            return DataTables::of($customers)
                ->editColumn(
                    'is_active',
                    fn($row) =>
                    $row->is_active
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>'
                )
                ->editColumn(
                    'last_login_at',
                    fn($row) =>
                    $row->last_login_at ? $row->last_login_at->format('d-M-Y h:i A') : '-'
                )
                ->editColumn(
                    'password_changed_at',
                    fn($row) =>
                    $row->password_changed_at ? $row->password_changed_at->format('d-M-Y h:i A') : '-'
                )
                ->addColumn('action', function ($row) {
                    $showUrl = route('admin.sales.customers.show', $row->id);
                    return view('theme.adminlte.components._table-actions', compact('showUrl'))->render();
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }

        return view('theme.adminlte.sales.customers.index');
    }

    public function show($id)
    {
        $user = User::with('orders')->findOrFail($id);

        return view('theme.adminlte.sales.customers.show', compact('user'));
    }
}
