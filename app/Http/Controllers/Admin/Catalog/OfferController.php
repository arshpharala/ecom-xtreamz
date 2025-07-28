<?php

namespace App\Http\Controllers\Admin\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Offer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductVariant;
use App\Http\Requests\StoreOfferRequest;
use App\Models\Catalog\OfferTranslation;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UpdateOfferRequest;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offers = Offer::withJoins()
                ->withSelection()
                ->withTrashed()
                ->groupBy('offers.id');

            return DataTables::of($offers)
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.catalog.offers.edit', $row->id);
                    $deleteUrl = route('admin.catalog.offers.destroy', $row->id);
                    $restoreUrl = route('admin.catalog.offers.restore', $row->id);
                    $editSidebar = true;
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'row', 'editSidebar'))->render();
                })
                ->editColumn('starts_at', function ($row) {
                    return $row->starts_at?->format('d-M-Y  h:m A');
                })
                ->editColumn('ends_at', function ($row) {
                    return $row->ends_at?->format('d-M-Y  h:m A');
                })
                ->addColumn('is_active', fn($row) => $row->deleted_at ? '<span class="badge badge-danger">Deleted</span>' : '<span class="badge badge-success">Active</span>')
                ->rawColumns(['action', 'is_active', 'starts_at', 'ends_at'])
                ->make(true);
        }
        return view('theme.adminlte.catalog.offers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['offer'] = new Offer();
        $response['view'] =  view('theme.adminlte.catalog.offers.create', $data)->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $offer = Offer::create([
                'discount_type'  => $validated['discount_type'],
                'discount_value' => $validated['discount_value'],
                'starts_at'      => $validated['starts_at'],
                'ends_at'        => $validated['ends_at'],
                'is_active'      => $validated['is_active'] ?? false,
            ]);


            foreach ($validated['title'] as $locale => $title) {
                OfferTranslation::create([
                    'offer_id'    => $offer->id,
                    'locale'      => $locale,
                    'title'       => $title,
                    'description' => $validated['description'][$locale] ?? null,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }


        return response()->json([
            'message' => 'Offer created successfully',
            'redirect' => route('admin.catalog.offers.index')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $offer = Offer::with('translations')->findOrFail($id);

        $data['offer'] = $offer;

        $response['view'] =  view('theme.adminlte.catalog.offers.edit', $data)->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferRequest $request, string $id)
    {
        $validated = $request->validated();

        $offer = Offer::findOrFail($id);


        $offer->update([
            'discount_type'  => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'starts_at'      => $validated['starts_at'],
            'ends_at'        => $validated['ends_at'],
            'is_active'      => $validated['is_active'] ?? false,
        ]);


        foreach ($validated['title'] as $locale => $title) {
            OfferTranslation::updateOrCreate(
                ['offer_id' => $offer->id, 'locale' => $locale],
                [
                    'title'       => $title,
                    'description' => $validated['description'][$locale] ?? null,
                ]
            );
        }

        return response()->json([
            'message'   => 'Offer updated successfully',
            'redirect'  => route('admin.catalog.offers.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('admin.catalog.offers.index')->with('success', 'Offer deleted successfully.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $offfer  = Offer::withTrashed()->findOrFail($id);
        $offfer->restore();

        return response()->json(['message' => 'Offer Restored.']);
    }
}
