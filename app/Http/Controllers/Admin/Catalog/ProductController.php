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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        if ($request->ajax()) {

            $query = Product::withTrashed()
                ->withJoins()
                ->withSelection()
                ->withFilters($request->only([
                    'search',
                    'status',
                    'category_id',
                    'brand_id',
                    'is_featured',
                    'is_new',
                    'show_in_slider',
                ]));

            return DataTables::of($query)
                ->editColumn('status', function ($row) {

                    if ($row->deleted_at) {
                        $status = '<span class="badge badge-danger">Deleted</span>';
                    } elseif ($row->is_active) {
                        $status = '<span class="badge badge-success">Active</span>';
                    } else {
                        $status = '<span class="badge badge-secondary">Inactive</span>';
                    }

                    return $status;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.catalog.products.edit', $row->id);
                    $deleteUrl = route('admin.catalog.products.destroy', $row->id);
                    $restoreUrl = route('admin.catalog.products.restore', $row->id);
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'row'))->render();
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $data['categories'] = Category::withJoins()->pluck('category_translations.name', 'categories.id');
        $data['brands']     = Brand::pluck('name', 'id');

        return view('theme.adminlte.catalog.products.index', $data);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Product::class);

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
        $this->authorize('create', Product::class);

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
            'message' => __('crud.created', ['name' => 'Product']),
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
        $product = Product::with([
            'translations',
            'attachments',
            'category.attributes.values'
        ])->findOrFail($id);

        $this->authorize('update', $product);;

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

        $this->authorize('update', $product);

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
            'message'   => __('crud.updated', ['name' => 'Product']),
            'redirect'  => route('admin.catalog.products.edit', $product->id),
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $this->authorize('delete', $product);

        $product->delete();

        return response()->json([
            'message'   => __('crud.deleted', ['name' => 'Product']),
            'redirect'  => route('admin.catalog.products.index'),
        ]);
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        $this->authorize('restore', $product);

        $product->restore();

        return response()->json([
            'message'   => __('crud.restored', ['name' => 'Product']),
            'redirect'  => route('admin.catalog.products.index'),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $this->authorize('bulk', Product::class);

        $request->validate(['ids' => 'required|array']);
        Product::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message'   => __('crud.deleted', ['name' => 'Product']),
            'redirect'  => route('admin.catalog.products.index'),
        ]);
    }

    public function bulkRestore(Request $request)
    {
        $this->authorize('bulk', Product::class);

        $request->validate(['ids' => 'required|array']);
        Product::withTrashed()->whereIn('id', $request->ids)->restore();

        return response()->json([
            'message'   => __('crud.restored', ['name' => 'Product']),
            'redirect'  => route('admin.catalog.products.index'),
        ]);
    }
}
