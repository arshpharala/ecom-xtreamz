<?php

namespace App\Http\Controllers\Admin\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreBrandRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UpdateBrandRequest;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $brands = Brand::withTrashed();
            return DataTables::of($brands)
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.catalog.brands.edit', $row->id);
                    $deleteUrl = route('admin.catalog.brands.destroy', $row->id);
                    $restoreUrl = route('admin.catalog.brands.restore', $row->id);
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
        return view('theme.adminlte.catalog.brands.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');

        Brand::create($data);

        return response()->json([
            'message' => 'Brand created!',
            'redirect' => route('admin.catalog.brands.index')
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
        $brand = Brand::findOrFail($id);

        $data['brand'] = $brand;

        $response['view'] =  view('theme.adminlte.catalog.brands.edit', $data)->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);

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
            'message' => 'Brand updated!',
            'redirect' => route('admin.catalog.brands.index')
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return response()->json(['message' => 'Brand deleted.']);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        $brand->restore();

        return response()->json(['message' => 'Brand Restored.']);
    }
}
