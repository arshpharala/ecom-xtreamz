<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\Subscriber;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subscribers = Subscriber::query();
            return DataTables::of($subscribers)
                // ->addColumn('action', function ($row) {
                //     $editUrl = route('admin.catalog.brands.edit', $row->id);
                //     $deleteUrl = route('admin.catalog.brands.destroy', $row->id);
                //     $restoreUrl = route('admin.catalog.brands.restore', $row->id);
                //     $editSidebar = true;
                //     return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'row', 'editSidebar'))->render();
                // })
                // ->editColumn('logo', function ($row) {
                //     return $row->logo
                //         ? '<img src="' . asset('storage/' . $row->logo) . '" class="img-sm">'
                //         : '';
                // })
                ->editColumn('subscribed_at', function ($row) {
                    return $row->subscribed_at?->format('d-M-Y  h:m A');
                })
                ->addColumn('status', fn($row) => $row->unsubscribed_at ? '<span class="badge badge-danger">Unsubscribed</span>' : '<span class="badge badge-success">Subscribed</span>')
                ->rawColumns(['status'])
                ->make(true);
        }
        return view('theme.adminlte.sales.subscribers.index');
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
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
