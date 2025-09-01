<?php

namespace App\Http\Controllers\Admin\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Vendor;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVendorRequest;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $vendors = Vendor::withTrashed();
            return DataTables::of($vendors)
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.catalog.vendors.edit', $row->id);
                    $deleteUrl = route('admin.catalog.vendors.destroy', $row->id);
                    $restoreUrl = route('admin.catalog.vendors.restore', $row->id);
                    $editSidebar = true;
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'row', 'editSidebar'))->render();
                })
                ->editColumn('logo', function ($row) {
                    return $row->logo
                        ? '<img src="' . asset('storage/' . $row->logo) . '" class="img-sm">'
                        : '';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->addColumn('is_active', fn($row) => $row->deleted_at ? '<span class="badge badge-danger">Deleted</span>' : '<span class="badge badge-success">Active</span>')
                ->rawColumns(['logo', 'action', 'is_active'])
                ->make(true);
        }
        return view('theme.adminlte.catalog.vendors.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response['view'] =  view('theme.adminlte.catalog.vendors.create')->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVendorRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('vendors', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');

        Vendor::create($data);

        return response()->json([
            'message'   => __('crud.created', ['name' => 'Vendor']),
            'redirect'  => route('admin.catalog.vendors.index')
        ]);
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
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return response()->json([
            'message'   => __('crud.deleted', ['name' => 'Vendor']),
            'redirect'  => route('admin.catalog.vendors.index')
        ]);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $brand = Vendor::withTrashed()->findOrFail($id);
        $brand->restore();

        return response()->json([
            'message'   => __('crud.restored', ['name' => 'Vendor']),
            'redirect'  => route('admin.catalog.vendors.index')
        ]);
    }
}
