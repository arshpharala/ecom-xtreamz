<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreAdminRequest;
use App\Http\Requests\Auth\UpdateAdminRequest;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $admins = Admin::query();
            return DataTables::of($admins)
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.auth.admins.edit', $row->id);
                    // $deleteUrl = route('admin.auth.admins.destroy', $row->id);
                    // $restoreUrl = route('admin.auth.admins.restore', $row->id);
                    $editSidebar = true;
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'row', 'editSidebar'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->addColumn('is_active', fn($row) => !$row->is_active ? '<span class="badge badge-danger">Inactive</span>' : '<span class="badge badge-success">Active</span>')
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }

        return view('theme.adminlte.auth.admin.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response['view'] =  view('theme.adminlte.auth.admin.create')->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active');
        $data['password'] = bcrypt($request['password']);

        Admin::create($data);

        return response()->json([
            'message' => 'User created!',
            'redirect' => route('admin.auth.admins.index')
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
        $admin = Admin::with('roles')->findOrFail($id);
        $roles = Role::get()->map(function($role) use($admin){
            $role->checked = $admin->roles->contains($role->id);

            return $role;
        });

        $data['roles'] = $roles;
        $data['admin'] = $admin;

        $response['view'] =  view('theme.adminlte.auth.admin.edit', $data)->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, string $id)
    {
        $admin = Admin::findOrFail($id);

        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request['password']);
        }

        $admin->update($data);
        $admin->roles()->sync($data['roles'] ?? null);

        return response()->json([
            'message' => 'User updated!',
            'redirect' => route('admin.auth.admins.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
