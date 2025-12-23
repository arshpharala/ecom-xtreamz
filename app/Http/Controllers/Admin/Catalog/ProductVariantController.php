<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Models\CMS\Tag;
use Illuminate\Http\Request;
use App\Models\CMS\Packaging;
use App\Models\Catalog\Product;
use App\Models\Catalog\Attribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductVariant;
use App\Http\Requests\StoreProductVariantRequest;
use App\Http\Requests\UpdateProductVariantRequest;
use App\Models\Catalog\ProductVariantPackaging;

class ProductVariantController extends Controller
{
    public function index($productId)
    {
        $product = Product::findOrFail($productId);

        if (request()->ajax()) {

            $variants = ProductVariant::where('product_id', $product->id)->with('tags', 'attributeValues.attribute', 'attachments')->get();

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

        $tags = Tag::get();
        $packagings = Packaging::where('is_active', 1)->get();
        $lastSKU = ProductVariant::latest()->limit(1)->value('sku');
        $data['lastSKU'] = $lastSKU;

        $data['attributes'] = $attributes;
        $data['product']    = $product;
        $data['tags']       = $tags;
        $data['packagings']       = $packagings;

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

            /* ===============================
           CREATE VARIANT (NEW ONLY)
        =============================== */
            $variant = new ProductVariant();
            $variant->product_id = $productId;
            $variant->sku        = $request->sku;
            $variant->price      = $request->price;
            $variant->stock      = $request->stock;
            $variant->is_primary = $request->boolean('is_primary');
            $variant->save();

            // ENSURE ONLY ONE PRIMARY
            if ($variant->is_primary) {
                ProductVariant::where('product_id', $productId)
                    ->where('id', '!=', $variant->id)
                    ->update(['is_primary' => false]);
            }

            /* ===============================
           ATTRIBUTES (OPTIONAL)
        =============================== */
            if ($request->filled('attributes')) {
                $variant->attributeValues()->sync(
                    array_filter(array_values($request->attributes))
                );
            }

            /* ===============================
           TAGS (OPTIONAL)
        =============================== */
            if ($request->filled('tags')) {
                $variant->tags()->sync(array_values($request->tags));
            }

            /* ===============================
           PACKAGING (OPTIONAL)
        =============================== */
            if ($request->filled('packaging')) {
                foreach ($request->packaging as $packagingId => $value) {

                    if (blank($value)) {
                        continue;
                    }

                    ProductVariantPackaging::create([
                        'product_variant_id' => $variant->id,
                        'packaging_id'       => $packagingId,
                        'value'              => $value,
                    ]);
                }
            }

            /* ===============================
           ATTACHMENTS (UNCHANGED)
        =============================== */
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
            'success'  => true,
            'message'  => 'Variant saved successfully.',
            'redirect' => route('admin.catalog.products.edit', ['product' => $productId]),
        ]);
    }


    public function edit($productId, $id)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::with('tags')->where('product_id', $product->id)->findOrFail($id);
        $category = $product->category->load('attributes.values');

        $attributes = $category->attributes->map(function ($attr) {
            return [
                'id' => $attr->id,
                'name' => $attr->name,
                'values' => $attr->values->map(fn($v) => ['id' => $v->id, 'value' => $v->value]),
            ];
        });

        $tags = Tag::get()->map(function ($tag) use ($variant) {
            $tag->checked = $variant->tags->contains($tag->id);
            return $tag;
        });
        $packagings = Packaging::where('is_active', 1)->get();
        $data['attributes'] = $attributes;
        $data['product']    = $product;
        $data['variant']    = $variant;
        $data['tags']       = $tags;
        $data['packagings']       = $packagings;

        $response['view'] = view('theme.adminlte.catalog.products.variants.edit', $data)->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    public function update(UpdateProductVariantRequest $request, $productId, $id)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::whereProductId($productId)->findOrFail($id);

        DB::beginTransaction();

        try {

            /* ===============================
           BASIC VARIANT DATA
        =============================== */
            $variant->update([
                'sku'   => $request->sku,
                'price' => $request->price,
                'stock' => $request->stock,
                'is_primary' => $request->boolean('is_primary')
            ]);

            // ENSURE ONLY ONE PRIMARY
            if ($variant->is_primary) {
                ProductVariant::where('product_id', $productId)
                    ->where('id', '!=', $variant->id)
                    ->update(['is_primary' => false]);
            }


            /* ===============================
           ATTRIBUTES (OPTIONAL)
        =============================== */
            if ($request->has('attributes')) {
                $variant->attributeValues()->sync(
                    array_filter(array_values($request->attributes))
                );
            }

            /* ===============================
           TAGS (OPTIONAL)
        =============================== */
            if ($request->has('tags')) {
                $variant->tags()->sync(array_values($request->tags ?? []));
            }

            /* ===============================
           PACKAGING (SYNC WITH DELETE)
        =============================== */
            $submittedPackaging = collect($request->packaging ?? [])
                ->filter(fn($value) => filled($value));

            \App\Models\Catalog\ProductVariantPackaging::where('product_variant_id', $variant->id)
                ->whereNotIn('packaging_id', $submittedPackaging->keys())
                ->delete();

            foreach ($submittedPackaging as $packagingId => $value) {
                \App\Models\Catalog\ProductVariantPackaging::updateOrCreate(
                    [
                        'product_variant_id' => $variant->id,
                        'packaging_id'       => $packagingId,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }

            /* ===============================
           ATTACHMENTS (UNCHANGED)
        =============================== */
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
            'success'  => true,
            'message'  => 'Variant updated successfully.',
            'redirect' => route('admin.catalog.products.edit', ['product' => $productId]),
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
