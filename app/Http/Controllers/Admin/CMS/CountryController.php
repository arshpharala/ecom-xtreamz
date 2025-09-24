<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Models\CMS\Country;
use App\Models\CMS\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CountryRepository;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CountryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CountryRepository $countryRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Country::class);

        if (request()->ajax()) {

            $query = Country::query()
                ->select('countries.id', 'countries.code', 'countries.name', 'countries.tax_label', 'countries.tax_percentage', 'currencies.code as currency_code', 'countries.created_at')
                ->leftJoin('currencies', 'currencies.id', 'countries.currency_id')
                ->groupBy('countries.id');

            return DataTables::of($query)

                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.cms.countries.edit', $row->id);
                    $deleteUrl = route('admin.cms.countries.destroy', $row->id);
                    // $restoreUrl = route('admin.cms.countries.restore', $row->id);
                    $editSidebar = true;
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'editSidebar', 'row'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('theme.adminlte.cms.countries.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Country::class);

        $data['currencies']     = Currency::pluck('code', 'id');
        $response['view']       = view('theme.adminlte.cms.countries.create', $data)->render();

        return response()->json([
            'success'   => true,
            'data'      => $response
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCountryRequest $request)
    {
        $this->authorize('create', Country::class);

        $data = $request->validated();

        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('countries', 'public');
        }

        $this->countryRepository->create($data);

        return response()->json([
            'success' => true,
            'message' => __('crud.created', ['name' => 'Country']),
            'redirect' => route('admin.cms.countries.index')
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
        $country                = Country::findOrFail($id);

        $this->authorize('update', $country);

        $currencies             = Currency::pluck('code', 'id');
        $data['country']        = $country;
        $data['currencies']     = $currencies;
        $response['view']       = view('theme.adminlte.cms.countries.edit', $data)->render();

        return response()->json([
            'success'   => true,
            'data'      => $response
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, string $id)
    {
        $country = Country::findOrFail($id);

        $this->authorize('update', $country);

        $data = $request->validated();

        if ($request->hasFile('icon')) {

            if ($country->icon && Storage::disk('public')->exists($country->icon)) {
                Storage::disk('public')->delete($country->icon);
            }
            $data['icon'] = $request->file('icon')->store('countries', 'public');
        } else {
            unset($data['icon']);
        }

        $this->countryRepository->update($data, $id);

        return response()->json([
            'message'   => __('crud.updated', ['name' => 'Country']),
            'redirect'  => route('admin.cms.countries.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $country = Country::findOrFail($id);

        $this->authorize('delete', $country);

        $isDeleted = $this->countryRepository->delete($id);

        if ($isDeleted) {
            return response()->json([
                'success' => true,
                'message' => __('crud.deleted', ['name' => 'Country']),
                'redirect'  => route('admin.cms.countries.index')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('crud.restrict_default_delete', ['name' => 'Country']),
                'redirect'  => route('admin.cms.countries.index')
            ], 400);
        }
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $brand = Country::withTrashed()->findOrFail($id);

        $this->authorize('restore', $country);

        $brand->restore();

        return response()->json([
            'message'   => __('crud.restored', ['name' => 'Country']),
            'redirect'  => route('admin.cms.countries.index')
        ]);
    }
}
