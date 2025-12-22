<?php

namespace App\Http\Controllers\Admin\CMS;

use Illuminate\Http\Request;
use App\Models\CMS\ApiSyncLog;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ApiSyncLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $logs = ApiSyncLog::query();

            return DataTables::of($logs)
                ->editColumn('success', function ($row) {
                    return $row->success
                        ? '<span class="badge badge-success">Success</span>'
                        : '<span class="badge badge-danger">Failed</span>';
                })
                ->editColumn('http_status', function ($row) {
                    return $row->http_status
                        ? '<span class="badge badge-info">' . $row->http_status . '</span>'
                        : '-';
                })
                ->editColumn('fetched_at', fn($row) => $row->fetched_at->format('Y-m-d H:i:s'))
                ->rawColumns(['success', 'http_status'])
                ->make(true);
        }

        return view('theme.adminlte.cms.api-sync-logs.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
