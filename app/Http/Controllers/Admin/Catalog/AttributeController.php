<?php

namespace App\Http\Controllers\Admin\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Attribute;
use App\Http\Controllers\Controller;
use App\Models\Catalog\AttributeValue;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Attribute::withTrashed()->select(['id', 'name', 'deleted_at', 'created_at']);
            return DataTables::of($query)
                ->addColumn('status', fn($row) => $row->deleted_at ? '<span class="badge badge-danger">Deleted</span>' : '<span class="badge badge-success">Active</span>')
                ->addColumn('action', function ($row) {
                    // Show restore or delete depending on soft delete state
                    $editUrl = route('admin.catalog.attributes.edit', $row->id);
                    $deleteUrl = route('admin.catalog.attributes.destroy', $row->id);
                    $restoreUrl = route('admin.catalog.attributes.restore', $row->id);
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'row'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('theme.adminlte.catalog.attributes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('theme.adminlte.catalog.attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttributeRequest $request)
    {
        $validated = $request->validated();
        $attribute = Attribute::create(['name' => $validated['name']]);
        foreach ($validated['values'] as $value) {
            $attribute->values()->create(['value' => $value]);
        }
        return response()->json(['message' => 'Attribute created successfully.', 'redirect' => route('admin.catalog.attributes.index')]);
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
        $attribute = Attribute::findOrFail($id);

        $data['attribute'] = $attribute;

        return view('theme.adminlte.catalog.attributes.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, $id)
    {
        $attribute = Attribute::withTrashed()->findOrFail($id);
        $validated = $request->validated();
        $attribute->update(['name' => $validated['name']]);
        $existingVals = $attribute->values->pluck('value')->toArray();

        foreach ($validated['att_value'] as $attVal) {
            AttributeValue::firstOrCreate(['attribute_id' => $attribute->id, 'value' => $attVal]);
        }
        $removedValues = array_diff($existingVals, $validated['att_value']);
        if (!empty($removedValues)) {
            AttributeValue::where('attribute_id', $attribute->id)->whereIn('value', $removedValues)->delete();
        }
        return response()->json(['message' => 'Attribute updated successfully.', 'redirect' => route('admin.catalog.attributes.index')]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();
    }

    public function restore($id)
    {
        $attribute = Attribute::withTrashed()->findOrFail($id);
        $attribute->restore();
        return response()->json(['message' => 'Attribute restored.']);
    }


    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Attribute::whereIn('id', $request->ids)->delete();
        return response()->json(['message' => 'Attributes deleted.']);
    }

    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Attribute::withTrashed()->whereIn('id', $request->ids)->restore();
        return response()->json(['message' => 'Attributes restored.']);
    }
}
