<?php

namespace App\Http\Controllers\Admin\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Category;
use App\Models\Catalog\Attribute;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $locale = app()->getLocale();

            $query = Category::withTrashed()->with('translations')->select('id', 'slug', 'icon', 'parent_id', 'position', 'is_visible', 'deleted_at', 'created_at');
            return DataTables::of($query)
                ->editColumn('name', function ($row) use ($locale) {
                    return $row->translations->where('locale', $locale)->first()?->name ?? $row->slug;
                })
                ->editColumn('icon', function ($row) {
                    return $row->icon
                        ? '<img src="' . asset('storage/' . $row->icon) . '" class="img-sm">'
                        : '';
                })
                ->addColumn('status', fn($row) => $row->deleted_at ? '<span class="badge badge-danger">Deleted</span>' : '<span class="badge badge-success">Active</span>')
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.catalog.categories.edit', $row->id);
                    $deleteUrl = route('admin.catalog.categories.destroy', $row->id);
                    $restoreUrl = route('admin.catalog.categories.restore', $row->id);
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'row'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->rawColumns(['action', 'status', 'icon'])
                ->make(true);
        }
        return view('theme.adminlte.catalog.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories  = Category::all();
        $attributes = Attribute::all();

        $data['categories'] = $categories;
        $data['attributes'] = $attributes;
        return view('theme.adminlte.catalog.categories.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('categories', 'public');
        }

        $category = Category::create([
            'slug'       => $validated['slug'],
            'icon'       => $validated['icon'] ?? null,
            'parent_id'  => $validated['parent_id'] ?? null,
            'position'   => $validated['position'] ?? 0,
            'is_visible' => $validated['is_visible'] ?? false,
        ]);
        foreach ($validated['name'] as $locale => $name) {
            $category->translations()->create(['locale' => $locale, 'name' => $name]);
        }
        if (!empty($validated['attributes'])) {
            $category->attributes()->sync($validated['attributes']);
        }

        return response()->json([
            'message'   => __('crud.created', ['name' => 'Category']),
            'redirect'  => route('admin.catalog.categories.index')
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
        $category       = Category::withTrashed()->with('translations', 'attributes')->findOrFail($id);
        $categories     = Category::where('id', '!=', $category->id)->get();
        $attributes     = Attribute::all();

        $data['category']       = $category;
        $data['categories']     = $categories;
        $data['attributes']     = $attributes;

        return view('theme.adminlte.catalog.categories.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $validated = $request->validated();
        $category = Category::withTrashed()->findOrFail($id);

        if ($request->hasFile('icon')) {

            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }
            $validated['icon'] = $request->file('icon')->store('categories', 'public');
        } else {
            unset($validated['icon']);
        }


        $category->update([
            'slug'       => $validated['slug'],
            'icon'       => $validated['icon'] ?? $category->icon,
            'parent_id'  => $validated['parent_id'] ?? null,
            'position'   => $validated['position'] ?? 0,
            'is_visible' => $validated['is_visible'] ?? false,
        ]);


        foreach ($validated['name'] as $locale => $name) {
            $translation = $category->translations()->firstOrNew(['locale' => $locale]);
            $translation->name = $name;
            $category->translations()->save($translation);
        }
        $category->attributes()->sync($validated['attributes'] ?? []);

        return response()->json([
            'message'   => __('crud.updated', ['name' => 'Category']),
            'redirect'  => route('admin.catalog.categories.index')
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message'   => __('crud.deleted', ['name' => 'Category']),
            'redirect'  => route('admin.catalog.categories.index')
        ]);
    }

    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();

        return response()->json([
            'message'   => __('crud.restored', ['name' => 'Category']),
            'redirect'  => route('admin.catalog.categories.index')
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Category::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message'   => __('crud.deleted', ['name' => 'Category']),
            'redirect'  => route('admin.catalog.categories.index')
        ]);
    }

    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Category::withTrashed()->whereIn('id', $request->ids)->restore();

        return response()->json([
            'message'   => __('crud.restored', ['name' => 'Category']),
            'redirect'  => route('admin.catalog.categories.index')
        ]);
    }
}
