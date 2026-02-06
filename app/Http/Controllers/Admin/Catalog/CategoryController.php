<?php

namespace App\Http\Controllers\Admin\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Category;
use App\Models\Catalog\Attribute;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

        if ($request->ajax()) {
            $locale = app()->getLocale();

            $query = Category::withTrashed()
                ->leftJoin(
                    'category_translations',
                    function ($join) use ($locale) {
                        $join->on('categories.id', '=', 'category_translations.category_id')
                            ->where('category_translations.locale', '=', $locale);
                    }
                )
                ->select(
                    'categories.id',
                    'categories.slug',
                    'categories.icon',
                    'categories.parent_id',
                    'categories.position',
                    'categories.is_visible',
                    'categories.show_on_homepage',
                    'categories.deleted_at',
                    'categories.created_at',
                    'category_translations.name'
                );

            return DataTables::of($query)
                ->editColumn('icon', function ($row) {
                    return $row->icon
                        ? '<img src="' . asset('storage/' . $row->icon) . '" class="img-sm">'
                        : '';
                })
                ->editColumn('is_visible', fn($row) => $row->is_visible ? '<span class="badge border border-success text-success">Visible</span>' : '<span class="badge border border-warning text-warning">Hidden</span>')
                ->addColumn('show_on_homepage', fn($row) => $row->show_on_homepage ? '<span class="badge border border-success text-success">Visible</span>' : '<span class="badge border border-warning text-warning">Hidden</span>')
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.catalog.categories.edit', $row->id);
                    $deleteUrl = route('admin.catalog.categories.destroy', $row->id);
                    $restoreUrl = route('admin.catalog.categories.restore', $row->id);
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'row'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->rawColumns(['action', 'is_visible', 'show_on_homepage', 'icon'])
                ->make(true);
        }
        return view('theme.adminlte.catalog.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Category::class);

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
        $this->authorize('create', Category::class);

        $validated = $request->validated();

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('categories', 'public');
        }

        $category = Category::create([
            'slug'       => $validated['slug'],
            'icon'       => $validated['icon'] ?? null,
            'parent_id'  => $validated['parent_id'] ?? null,
            'position'   => $validated['position'] ?? 0,
            'is_visible' => $validated['is_visible'] ?? false,
            'show_in_menu' => $validated['show_in_menu'] ?? false,
            'discount_type' => $validated['discount_type'] ?? null,
            'discount_value' => $validated['discount_value'] ?? null,
            'valid_forever' => $request->has('valid_forever'),
            'valid_till' => $validated['valid_till'] ?? null,
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
        $category = Category::withTrashed()->with('translations', 'attributes')->findOrFail($id);

        $this->authorize('update', $category);

        $categories     = Category::where('id', '!=', $category->id)->get();
        $attributes     = Attribute::all();

        $menuTags = collect(config('menu.header'))
            ->filter(fn($item) => ($item['type'] ?? null) === 'category')
            ->mapWithKeys(fn($item, $key) => [$key => $item['label']]);

        $data['category']       = $category;
        $data['categories']     = $categories;
        $data['attributes']     = $attributes;
        $data['menuTags']       = $menuTags;

        return view('theme.adminlte.catalog.categories.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        $this->authorize('update', $category);

        $validated = $request->validated();

        if ($request->hasFile('icon')) {

            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }
            $validated['icon'] = $request->file('icon')->store('categories', 'public');
        } else {
            unset($validated['icon']);
        }

        if ($request->hasFile('image')) {

            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        } else {
            unset($validated['image']);
        }

        if ($request->hasFile('banner_image')) {

            if ($category->banner_image && Storage::disk('public')->exists($category->banner_image)) {
                Storage::disk('public')->delete($category->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('categories', 'public');
        } else {
            unset($validated['banner_image']);
        }


        $category->update([
            'slug'              => $validated['slug'],
            'icon'              => $validated['icon'] ?? $category->icon,
            'parent_id'         => $validated['parent_id'] ?? null,
            'position'          => $validated['position'] ?? 0,
            'is_visible'        => $validated['is_visible'] ?? false,
            'show_in_menu'      => $validated['show_in_menu'] ?? false,
            'show_on_homepage'  => $validated['show_on_homepage'] ?? false,
            'image'             => $validated['image'] ?? $category->image,
            'banner_image'      => $validated['banner_image'] ?? $category->banner_image,
            'text_color'        => $validated['text_color'] ?? $category->text_color,
            'background_color'  => $validated['background_color'] ?? $category->background_color,
            'menu_tag'          => $validated['menu_tag'] ?? null,
            'discount_type'     => $validated['discount_type'] ?? null,
            'discount_value'    => $validated['discount_value'] ?? null,
            'valid_forever'     => $request->has('valid_forever'),
            'valid_till'        => $validated['valid_till'] ?? null,
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

        $this->authorize('delete', $category);

        $category->delete();

        return response()->json([
            'message'   => __('crud.deleted', ['name' => 'Category']),
            'redirect'  => route('admin.catalog.categories.index')
        ]);
    }

    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);

        $this->authorize('restore', $category);

        $category->restore();

        return response()->json([
            'message'   => __('crud.restored', ['name' => 'Category']),
            'redirect'  => route('admin.catalog.categories.index')
        ]);
    }


    public function bulkDelete(Request $request)
    {
        $this->authorize('bulk', Category::class);

        $request->validate(['ids' => 'required|array']);
        Category::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message'   => __('crud.deleted', ['name' => 'Category']),
            'redirect'  => route('admin.catalog.categories.index')
        ]);
    }


    public function bulkRestore(Request $request)
    {
        $this->authorize('bulk', Category::class);

        $request->validate(['ids' => 'required|array']);
        Category::withTrashed()->whereIn('id', $request->ids)->restore();

        return response()->json([
            'message'   => __('crud.restored', ['name' => 'Category']),
            'redirect'  => route('admin.catalog.categories.index')
        ]);
    }
}
