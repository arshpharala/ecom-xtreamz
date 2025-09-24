<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Enums\Position;
use App\Models\CMS\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CurrencyRepository;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreCurrencyRequest;
use App\Http\Requests\UpdateCurrencyRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CurrencyController extends Controller
{
    use AuthorizesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CurrencyRepository $currencyRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Currency::class);

        if (request()->ajax()) {

            $query = Currency::withTrashed();

            return DataTables::of($query)

                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.cms.currencies.edit', $row->id);
                    $deleteUrl = route('admin.cms.currencies.destroy', $row->id);
                    $restoreUrl = route('admin.cms.currencies.restore', $row->id);
                    $editSidebar = true;
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'editSidebar', 'row'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('theme.adminlte.cms.currencies.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Currency::class);

        $positions              = Position::cases();
        $data['positions']      = $positions;
        $response['view']       = view('theme.adminlte.cms.currencies.create', $data)->render();

        return response()->json([
            'success'   => true,
            'data'      => $response
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCurrencyRequest $request)
    {
        $this->authorize('create', Currency::class);

        $data = $request->validated();

        $this->currencyRepository->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Currency created successfully.',
            'redirect' => route('admin.cms.currencies.index')
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
        $currency               = Currency::findOrFail($id);

        $this->authorize('update', $currency);

        $positions              = Position::cases();
        $data['currency']       = $currency;
        $data['positions']      = $positions;
        $response['view']       = view('theme.adminlte.cms.currencies.edit', $data)->render();

        return response()->json([
            'success'   => true,
            'data'      => $response
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRequest $request, string $id)
    {
        $currency = Currency::findOrFail($id);

        $this->authorize('update', $currency);

        $data = $request->validated();

        $this->currencyRepository->update($data, $id);

        return response()->json([
            'success' => true,
            'message' => 'Currency updated successfully.',
            'redirect' => route('admin.cms.currencies.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $currency = Currency::findOrFail($id);

        $this->authorize('delete', $currency);
        
        $isDeleted = $this->currencyRepository->delete($id);

        if ($isDeleted) {
            return response()->json([
                'success' => true,
                'message' => 'Currency Deleted successfully.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "You can't delete default currency",
            ], 400);
        }
    }
}
