<?php

namespace App\Http\Controllers\Admin\Cataloge;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if (request()->ajax()) {
            $locale = app()->getLocale();

            $query = Category::with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }])->select('id', 'slug', 'created_at');

            return DataTables::of($query)
                ->addColumn('name', function ($category) use ($locale) {
                    return $category->translation($locale)?->name ?? '';
                })
                ->addColumn('action', function ($category) {
                    $data['editUrl'] = route('admin.catalog.categories.edit', $category);
                    $data['deleteUrl'] = route('admin.catalog.categories.destroy', $category);

                    return view('theme.adminlte.components.table-actions', $data)->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('theme.adminlte.catalog.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('theme.adminlte.catalog.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug'      => 'required|unique:categories,slug',
            'name'      => 'required|array',
            'name.*'    => 'required|string|max:255',
        ]);

        $category = Category::create([
            'slug'      => $validated['slug']
        ]);

        foreach ($validated['name'] as $locale => $name) {
            $category->translations()->create([
                'locale'    => $locale,
                'name'      => $name,
            ]);
        }

        return response()->json([
            'message' => 'Category created successfully.',
            'redirect' => route('admin.catalog.categories.index'),
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
        $category           = Category::findOrFail($id);
        $data['category']   = $category;

        return view('theme.adminlte.catalog.categories.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'slug' => 'required|unique:categories,slug,' . $id,
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);

        $category->update(['slug' => $validated['slug']]);

        foreach ($validated['name'] as $locale => $name) {
            $translation = $category->translations()->firstOrNew(['locale' => $locale]);
            $translation->name = $name;
            $category->translations()->save($translation);
        }

        return response()->json([
            'message' => 'Category Updated successfully.',
            'redirect' => route('admin.catalog.categories.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
