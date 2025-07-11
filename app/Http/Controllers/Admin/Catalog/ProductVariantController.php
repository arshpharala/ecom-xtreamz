<?php

namespace App\Http\Controllers\Admin\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use App\Models\Catalog\Attribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductVariant;
use App\Http\Requests\StoreProductVariantRequest;
use App\Http\Requests\UpdateProductVariantRequest;

class ProductVariantController extends Controller
{
    public function index($productId)
    {
        $product = Product::findOrFail($productId);

        if (request()->ajax()) {

            $variants = ProductVariant::where('product_id', $product->id)->with('attributeValues.attribute', 'attachments')->get();

            $data['product'] = $product;
            $data['variants'] = $variants;

            $response['view'] = view('theme.adminlte.catalog.products.variants.index', $data)->render();

            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        }
    }

    public function create($productId)
    {

        $product = Product::findOrFail($productId);

        $category = $product->category->load('attributes.values');

        $attributes = $category->attributes->map(function ($attr) {
            return [
                'id' => $attr->id,
                'name' => $attr->name,
                'values' => $attr->values->map(fn($v) => ['id' => $v->id, 'value' => $v->value]),
            ];
        });

        $data['attributes'] = $attributes;
        $data['product'] = $product;

        $response['view'] = view('theme.adminlte.catalog.products.variants.create', $data)->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }


    public function store(StoreProductVariantRequest $request, $productId)
    {
        $product = Product::findOrFail($productId);

        DB::beginTransaction();
        try {

            $variant = ProductVariant::withTrashed()->firstOrNew(['product_id' => $productId, 'id' => $request['id'] ?? null]);

            $variant->sku = $request['sku'];
            $variant->price = $request['price'];
            $variant->stock = $request['stock'];
            $variant->deleted_at = null;
            $variant->save();

            $variant->attributeValues()->sync(array_values($request['attributes']));

            $variant->shipping()->updateOrCreate(
                [],
                [
                    'length' => $request['length'],
                    'width'  => $request['width'],
                    'height' => $request['height'],
                    'weight' => $request['weight'],
                ]
            );

            // Attachments (keep old, add new)
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $variant->attachments()->create([
                        'file_path' => $file->store('attachments', 'public'),
                        'file_type' => $file->getMimeType(),
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return response()->json([
            'success' => true,
            'message' => 'Variant Added Successfully.',
            'redirect' => route('admin.catalog.products.edit', ['product' => $productId])
        ]);
    }

    public function edit($productId, $id)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::where('product_id', $product->id)->findOrFail($id);

        $category = $product->category->load('attributes.values');

        $attributes = $category->attributes->map(function ($attr) {
            return [
                'id' => $attr->id,
                'name' => $attr->name,
                'values' => $attr->values->map(fn($v) => ['id' => $v->id, 'value' => $v->value]),
            ];
        });

        $data['attributes'] = $attributes;
        $data['product'] = $product;
        $data['variant'] = $variant;

        $response['view'] = view('theme.adminlte.catalog.products.variants.edit', $data)->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    public function update(UpdateProductVariantRequest $request, $productId, $id)
    {

        $product    = Product::findOrFail($productId);
        $variant    = ProductVariant::whereProductId($productId)->findOrFail($id);

        DB::beginTransaction();

        try {
            $variant->sku           = $request['sku'];
            $variant->price         = $request['price'];
            $variant->stock         = $request['stock'];
            // $variant->deleted_at    = null;
            $variant->save();

            $variant->attributeValues()->sync(array_values($request['attributes']));

            $variant->shipping()->updateOrCreate(
                [],
                [
                    'length' => $request['length'],
                    'width'  => $request['width'],
                    'height' => $request['height'],
                    'weight' => $request['weight'],
                ]
            );


            // Attachments (keep old, add new)
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $variant->attachments()->create([
                        'file_path' => $file->store('attachments', 'public'),
                        'file_type' => $file->getMimeType(),
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return response()->json([
            'success' => true,
            'message' => 'Variant Updated Successfully.',
            'redirect' => route('admin.catalog.products.edit', ['product' => $productId])
        ]);
    }


    public function storeMultiple(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        DB::beginTransaction();

        try {

            foreach ($request->variants as $variantData) {

                $variant = ProductVariant::withTrashed()->firstOrNew(['product_id' => $productId, 'id' => $variantData['id'] ?? null]);

                $variant->sku = $variantData['sku'];
                $variant->price = $variantData['price'];
                $variant->stock = $variantData['stock'];
                $variant->deleted_at = null;
                $variant->save();

                $variant->attributeValues()->sync(array_values($variantData['attributes']));
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function destroy($productId, $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::withTrashed()->where('product_id', $productId)->findOrFail($variantId);

        DB::beginTransaction();
        try {

            $variant->attributeValues()->sync([]); // detach all attributes
            $variant->attachments()->delete(); // delete all attachments
            $variant->forceDelete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
