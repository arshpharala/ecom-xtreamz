<?php

namespace App\Http\Controllers\Admin\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreBrandRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BrandController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', Brand::class);

        if ($request->ajax()) {
            $brands = Brand::withTrashed();
            return DataTables::of($brands)
                ->addColumn('action', function ($row) {
                    $compact['editUrl'] = route('admin.catalog.brands.edit', $row->id);
                    $compact['deleteUrl'] = route('admin.catalog.brands.destroy', $row->id);
                    $compact['restoreUrl'] = route('admin.catalog.brands.restore', $row->id);
                    $compact['editSidebar'] = true;
                    return view('theme.adminlte.components._table-actions', $compact)->render();
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
        return view('theme.adminlte.catalog.brands.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Brand::class);

        $response['view'] =  view('theme.adminlte.catalog.brands.create')->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $this->authorize('create', Brand::class);

        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');

        Brand::create($data);

        return response()->json([
            'message'   => __('crud.created', ['name' => 'Brand']),
            'redirect'  => route('admin.catalog.brands.index')
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
    public function edit($id)
    {
        $brand          = Brand::findOrFail($id);

        $this->authorize('update', $brand);

        $data['brand'] = $brand;

        $response['view'] =  view('theme.adminlte.catalog.brands.edit', $data)->render();

        return response()->json([
            'success'   => true,
            'data'      => $response
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $this->authorize('update', $brand);

        $data = $request->validated();

        if ($request->hasFile('logo')) {

            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        } else {
            unset($data['logo']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $brand->update($data);

        return response()->json([
            'message'   => __('crud.updated', ['name' => 'Brand']),
            'redirect'  => route('admin.catalog.brands.index')
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        $this->authorize('delete', $brand);

        $brand->delete();

        return response()->json([
            'message'   => __('crud.deleted', ['name' => 'Brand']),
            'redirect'  => route('admin.catalog.brands.index')
        ]);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        $brand->restore();

        return response()->json([
            'message'   => __('crud.restored', ['name' => 'Brand']),
            'redirect'  => route('admin.catalog.brands.index')
        ]);
    }
}
