<?php

namespace App\Http\Controllers\Admin\Cataloge;

use Illuminate\Http\Request;
use App\Models\Catalog\Attribute;
use App\Http\Controllers\Controller;
use App\Models\Catalog\AttributeValue;
use Yajra\DataTables\Facades\DataTables;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = Attribute::select(['id', 'name', 'created_at']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.catalog.attributes.edit', $row->id);
                    $deleteUrl = route('admin.catalog.attributes.destroy', $row->id);

                    return view('theme.adminlte.components.table-actions', compact('editUrl', 'deleteUrl'))->render();
                })
                ->rawColumns(['action'])
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required|array|min:1',
            'values.*' => 'required|string|max:255'
        ]);

        $attribute = Attribute::create(['name' => $validated['name']]);

        foreach ($validated['values'] as $value) {
            $attribute->values()->create(['value' => $value]);
        }

        return response()->json([
            'message' => 'Attribute created successfully.',
            'redirect' => route('admin.catalog.attributes.index'),
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
        $attribute = Attribute::findOrFail($id);

        $data['attribute'] = $attribute;

        return view('theme.adminlte.catalog.attributes.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attribute = Attribute::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'att_value' => 'array'
        ]);

        $attribute->update(['name' => $validated['name']]);

        $existingVals = $attribute->values->pluck('value')->toArray();

        foreach ($request->att_value as $index => $attVal) {

            AttributeValue::firstOrCreate([
                'attribute_id' => $attribute->id,
                'value' => $attVal
            ]);
        }

        $removedValues = array_diff($existingVals, $request->att_value);

        if (!empty($removedValues)) {
            AttributeValue::where('attribute_id', $attribute->id)->whereIn('value', $removedValues)->delete();
        }

        return response()->json([
            'message' => 'Attribute updated successfully.',
            'redirect' => route('admin.catalog.attributes.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();
    }
}
