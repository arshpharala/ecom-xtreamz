<?php

namespace App\Http\Controllers\Admin\Cataloge;

use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use App\Models\Catalog\Category;
use App\Models\Catalog\Attribute;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Catalog\ProductTranslation;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Product::with('category.translations', 'translations');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->translation()?->name ?? '-';
                })
                ->editColumn('category', function ($row) {
                    return $row->category?->translation()?->name ?? '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('Y-m-d');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.catalog.products.edit', $row->id);
                    $deleteUrl = route('admin.catalog.products.destroy', $row->id);

                    return view('theme.adminlte.components.table-actions', compact('editUrl', 'deleteUrl'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('theme.adminlte.catalog.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['categories'] = Category::with('translations')->get();
        return view('theme.adminlte.catalog.products.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
            'description' => 'nullable|array',
        ]);

        $product = Product::create([
            'slug' => $request->slug,
            'category_id' => $request->category_id,
        ]);

        foreach (active_locals() as $locale) {
            ProductTranslation::create([
                'product_id' => $product->id,
                'locale' => $locale,
                'name' => $request->name[$locale] ?? '',
                'description' => $request->description[$locale] ?? null,
            ]);
        }

        return response()->json([
            'message' => 'Product created successfully.',
            'redirect' => route('admin.catalog.products.index'),
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
        $data['product'] = Product::with('translations')->findOrFail($id);
        $data['categories'] = Category::with('translations')->get();
        $attributes = Attribute::get();
        $data['attributes'] = $attributes;

        return view('theme.adminlte.catalog.products.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'slug' => 'required|unique:products,slug,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
            'description' => 'nullable|array',
        ]);

        $product->update([
            'slug' => $request->slug,
            'category_id' => $request->category_id,
        ]);

        foreach (active_locals() as $locale) {
            $product->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $request->name[$locale] ?? '',
                    'description' => $request->description[$locale] ?? null,
                ]
            );
        }

        return response()->json([
            'message' => 'Product updated successfully.',
            'redirect' => route('admin.catalog.products.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.',
            'redirect' => route('admin.catalog.products.index'),
        ]);
    }
}
