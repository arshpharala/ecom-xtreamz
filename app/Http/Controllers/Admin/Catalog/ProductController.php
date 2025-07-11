<?php

namespace App\Http\Controllers\Admin\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Brand;
use App\Models\Catalog\Product;
use App\Models\Catalog\Category;
use App\Models\Catalog\Attribute;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductVariant;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreProductRequest;
use App\Models\Catalog\ProductTranslation;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Seo\Keyword;
use App\Models\Seo\Meta;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $locale = app()->getLocale();

            $query = Product::withTrashed()->with([
                'translations',
                'category.translations',
                'brand'
            ]);

            return DataTables::of($query)
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->editColumn('name', function ($row) use ($locale) {
                    return $row->translations->where('locale', $locale)->first()?->name ?? $row->slug;
                })
                ->editColumn('slug', function ($row) {
                    return $row->slug;
                })
                ->editColumn('category', function ($row) use ($locale) {
                    return $row->category
                        ? ($row->category->translations->where('locale', $locale)->first()?->name ?? $row->category->slug)
                        : '-';
                })
                ->editColumn('brand', function ($row) use ($locale) {
                    // Otherwise, just name
                    return $row->brand->name ?? '-';
                })
                ->editColumn('status', function ($row) {
                    return $row->is_active
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-secondary">Inactive</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.catalog.products.edit', $row->id);
                    $deleteUrl = route('admin.catalog.products.destroy', $row->id);
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl'))->render();
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('theme.adminlte.catalog.products.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::with(['translations'])->get();
        $brands         = Brand::all();


        $data['categories']     = $categories;
        $data['brands']         = $brands;

        $response['view'] = view('theme.adminlte.catalog.products.create', $data)->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // 1. Create the Product
        $product = Product::create([
            'slug'        => $request->slug,
            'category_id' => $request->category_id,
            'brand_id'    => $request->brand_id,
            'position'    => $request->position ?? 0,
            'is_active'        => $request->boolean('is_active'),
            'is_featured'      => $request->boolean('is_featured'),
            'is_new'           => $request->boolean('is_new'),
            'show_in_slider'   => $request->boolean('show_in_slider'),
        ]);

        // 2. Translations
        foreach (active_locals() as $locale) {
            $product->translations()->create([
                'locale'      => $locale,
                'name'        => $request->input("name.$locale"),
                'description' => $request->input("description.$locale"),
            ]);
        }

        return response()->json([
            'message' => 'Product created successfully!',
            'redirect' => route('admin.catalog.products.edit', $product->id),
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
        $defaultLocale = app()->getLocale();
        $product = Product::with([
            'translations',
            'attachments',
            'category.attributes.values'
        ])->findOrFail($id);

        $categories = Category::with('translations')->get();
        $brands     = Brand::all();


        $data['product']            = $product;
        $data['categories']         = $categories;
        $data['brands']             = $brands;

        return view('theme.adminlte.catalog.products.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        // Update main fields
        $product->update([
            'slug'        => $request->slug,
            // 'category_id' => $request->category_id,
            'brand_id'    => $request->brand_id,
            'position'    => $request->position ?? 0,
            'is_active'        => $request->boolean('is_active'),
            'is_featured'      => $request->boolean('is_featured'),
            'is_new'           => $request->boolean('is_new'),
            'show_in_slider'   => $request->boolean('show_in_slider'),
        ]);

        // Translations
        foreach (active_locals() as $locale) {
            $product->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name'        => $request->input("name.$locale"),
                    'description' => $request->input("description.$locale"),
                ]
            );
        }

        Meta::store($request, $product);

        return response()->json([
            'message' => 'Product updated successfully!',
            'redirect' => route('admin.catalog.products.edit', $product->id),
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted.']);
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        return response()->json(['message' => 'Product restored.']);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Product::whereIn('id', $request->ids)->delete();
        return response()->json(['message' => 'Products deleted.']);
    }

    public function bulkRestore(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Product::withTrashed()->whereIn('id', $request->ids)->restore();
        return response()->json(['message' => 'Products restored.']);
    }
}
