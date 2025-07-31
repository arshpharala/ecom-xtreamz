<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Models\Cart\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Catalog\ProductVariant;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $coupons = Coupon::query()
                ->select('id', 'code', 'type', 'value', 'start_at', 'end_at', 'min_cart_amount', 'is_active', 'created_at');

            return DataTables::of($coupons)
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.catalog.coupons.edit', $row->id);
                    $deleteUrl = route('admin.catalog.coupons.destroy', $row->id);
                    // $restoreUrl = route('admin.catalog.coupons.restore', $row->id);
                    $editSidebar = true;
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'row'))->render();
                })
                ->editColumn('start_at', function ($row) {
                    return $row->start_at?->format('d-M-Y  h:m A');
                })
                ->editColumn('end_at', function ($row) {
                    return $row->end_at?->format('d-M-Y  h:m A');
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->addColumn('is_active', fn($row) => !$row->is_active ? '<span class="badge badge-danger">Inactive</span>' : '<span class="badge badge-success">Active</span>')
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('theme.adminlte.catalog.coupons.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $variants = ProductVariant::with('product.translation')->get();
        return view('theme.adminlte.catalog.coupons.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCouponRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $data['scope'] = empty($request->product_variant_ids) ? 'cart' : 'variant';

            $coupon = Coupon::create($data);
            $coupon->variants()->sync($request->product_variant_ids ?? []);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return response()->json([
            'message'   => __('messages.coupon_created'),
            'redirect'  => route('admin.catalog.coupons.index')
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
        $coupon     = Coupon::findOrFail($id);
        $variants   = ProductVariant::with('product.translation')->get();

        $variants = $variants->pluck('product.translation.name', 'id')->map(function ($name, $id) use ($variants) {
            $sku = $variants->find($id)?->sku;
            return "$sku - $name";
        });

        $selected   = $coupon->variants()->pluck('id')->toArray();

        return view('theme.adminlte.catalog.coupons.edit', compact('coupon', 'variants', 'selected'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, string $id)
    {
        $data       = $request->validated();
        $coupon     = Coupon::findOrFail($id);

        DB::beginTransaction();

        try {
            $data['scope'] = empty($request->product_variant_ids) ? 'cart' : 'variant';
            $coupon->update($data);
            $coupon->variants()->sync($request->product_variant_ids ?? []);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return response()->json([
            'message'   => __('crud.updated', ['name' => 'Coupon']),
            'redirect'  => route('admin.catalog.coupons.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json([
            'message'   => __('crud.deleted', ['name' => 'Coupon']),
            'redirect' => route('admin.catalog.coupons.index')
        ]);
    }
}
