<?php

namespace App\Http\Controllers\Admin\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sales\ReturnReason;
use Yajra\DataTables\Facades\DataTables;

class ReturnReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $reasons = ReturnReason::withTrashed();
            return DataTables::of($reasons)
                ->addColumn('action', function ($row) {
                    $compact['editUrl'] = route('admin.sales.return-reasons.edit', $row->id);
                    $compact['deleteUrl'] = route('admin.sales.return-reasons.destroy', $row->id);
                    $compact['restoreUrl'] = route('admin.sales.return-reasons.restore', $row->id);
                    $compact['editSidebar'] = true;
                    return view('theme.adminlte.components._table-actions', $compact)->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:i A');
                })
                ->addColumn('is_active', fn($row) => $row->deleted_at 
                    ? '<span class="badge badge-danger">Deleted</span>' 
                    : ($row->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Inactive</span>'))
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('theme.adminlte.sales.return-reasons.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response['view'] =  view('theme.adminlte.sales.return-reasons.create')->render();
        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'reason' => 'required|string|max:255|unique:return_reasons,reason',
            'is_active' => 'boolean'
        ]);

        $data['is_active'] = $request->boolean('is_active');

        ReturnReason::create($data);

        return response()->json([
            'message'   => 'Return Reason created.',
            'redirect'  => route('admin.sales.return-reasons.index')
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
        $reason = ReturnReason::findOrFail($id);
        $data['reason'] = $reason;
        $response['view'] =  view('theme.adminlte.sales.return-reasons.edit', $data)->render();
        return response()->json([
            'success'   => true,
            'data'      => $response
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $reason = ReturnReason::findOrFail($id);
        
        $data = $request->validate([
            'reason' => 'required|string|max:255|unique:return_reasons,reason,' . $id,
            'is_active' => 'boolean'
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $reason->update($data);

        return response()->json([
            'message'   => 'Return Reason updated.',
            'redirect'  => route('admin.sales.return-reasons.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ReturnReason::findOrFail($id)->delete();
        return response()->json([
            'message'   => 'Return Reason deleted.',
            'redirect'  => route('admin.sales.return-reasons.index')
        ]);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        ReturnReason::withTrashed()->findOrFail($id)->restore();
        return response()->json([
            'message'   => 'Return Reason restored.',
            'redirect'  => route('admin.sales.return-reasons.index')
        ]);
    }
}
