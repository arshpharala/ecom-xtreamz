<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Models\CMS\Tag;
use Illuminate\Http\Request;
use App\Models\Catalog\Brand;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Yajra\DataTables\Facades\DataTables;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tags = Tag::query()
                ->withTrashed();

            return DataTables::of($tags)
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.cms.tags.edit', $row->id);
                    $deleteUrl = route('admin.cms.tags.destroy', $row->id);
                    $restoreUrl = route('admin.cms.tags.restore', $row->id);
                    $editSidebar = true;
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'row', 'editSidebar'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->addColumn('status', function ($row) {
                    if ($row->deleted_at) {
                        return '<span class="badge badge-secondary">Deleted</span>';
                    }

                    return $row->is_active
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('status_sort', function ($row) {
                    if ($row->deleted_at) return '3';      // Deleted highest priority
                    return $row->is_active ? '1' : '2';    // Active lowest, Inactive medium
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('theme.adminlte.cms.tags.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response['view'] =  view('theme.adminlte.cms.tags.create')->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active');

        Tag::create($data);

        return response()->json([
            'message'   => __('crud.created', ['name' => 'Tag']),
            'redirect'  => route('admin.cms.tags.index')
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
        $tag = Tag::findOrFail($id);
        $data['tag'] = $tag;

        $response['view'] =  view('theme.adminlte.cms.tags.edit', $data)->render();

        return response()->json([
            'success'   => true,
            'data'      => $response
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, string $id)
    {
        $tag                = Tag::findOrFail($id);
        $data               = $request->validated();
        $data['is_active']  = $request->boolean('is_active');
        $tag->update($data);

        return response()->json([
            'message'   => __('crud.updated', ['name' => 'Brand']),
            'redirect'  => route('admin.cms.tags.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return response()->json([
            'message'   => __('crud.deleted', ['name' => 'Tag']),
            'redirect'  => route('admin.cms.tags.index')
        ]);
    }


    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $tag = Tag::withTrashed()->findOrFail($id);
        $tag->restore();

        return response()->json([
            'message'   => __('crud.restored', ['name' => 'Tag']),
            'redirect'  => route('admin.cms.tags.index')
        ]);
    }
}
