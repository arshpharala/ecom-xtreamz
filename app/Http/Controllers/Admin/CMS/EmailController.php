<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Models\CMS\Email;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $emails = Email::query()
                ->select(
                    'emails.id',
                    'emails.reference',
                    'emails.template',
                    'emails.is_active'
                )
                ->withCount(['to', 'cc', 'bcc', 'exclude']);

            return DataTables::of($emails)
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.cms.emails.edit', $row->id);
                    $deleteUrl = route('admin.cms.emails.destroy', $row->id);
                    $restoreUrl = route('admin.cms.emails.restore', $row->id);
                    $editSidebar = false;
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'row', 'editSidebar'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->editColumn('is_active', function ($row) {
                    if ($row->deleted_at) {
                        return '<span class="badge badge-danger">Deleted</span>';
                    }elseif ($row->is_active) {
                        return '<span class="badge badge-success">Active</span>';
                    }else{
                        return '<span class="badge badge-warning">Inactive</span>';
                    }
                })
                ->rawColumns(['action', 'is_active', 'created_at'])
                ->make(true);
        }
        return view('theme.adminlte.cms.emails.index');
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

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $email  = Email::withTrashed()->findOrFail($id);
        $email->restore();

        return response()->json([
            'message'   => __('crud.restored', ['name' => 'Email']),
        ]);
    }
}
