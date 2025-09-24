<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmailRequest;
use App\Http\Requests\StoreEmailAdminRequest;
use App\Models\Admin;
use App\Models\CMS\Email;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmailController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Email::class);

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
                    } elseif ($row->is_active) {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
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
        $this->authorize('create', Email::class);

        return view('theme.adminlte.cms.emails.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmailRequest $request)
    {
        $this->authorize('create', Email::class);

        Email::create($request->validated());

        return response()->json([
            'message' => __('crud.created', ['name' => 'Email']),
            'redirect' => route('admin.cms.emails.index')
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
        $email = Email::with('to', 'cc', 'bcc', 'exclude')->findOrFail($id);

        $this->authorize('update', $email);

        $adminReceipients = $email->recipients()->get();

        $admins = Admin::whereNotIn('id', $adminReceipients->pluck('id'))->pluck('email', 'id');

        $data['email'] = $email;
        $data['admins'] = $admins;
        $data['adminRecipients'] = $adminReceipients;

        return view('theme.adminlte.cms.emails.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $email = Email::findOrFail($id);

        $this->authorize('update', $email);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $email = Email::findOrFail($id);

        $this->authorize('delete', $email);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $email = Email::withTrashed()->findOrFail($id);

        $this->authorize('restore', $email);

        $email->restore();

        return response()->json([
            'message'   => __('crud.restored', ['name' => 'Email']),
        ]);
    }
}
