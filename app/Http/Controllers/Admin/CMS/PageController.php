<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Models\CMS\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {

            $query = Page::query();

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    // Show restore or delete depending on soft delete state
                    $editUrl = route('admin.cms.pages.edit', $row->id);
                    $deleteUrl = route('admin.cms.pages.destroy', $row->id);
                    $restoreUrl = route('admin.cms.pages.restore', $row->id);
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'row'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('theme.adminlte.cms.pages.index');
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
        //
    }
}
