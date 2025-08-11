<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Models\CMS\Email;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmailAdminRequest;

class EmailAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreEmailAdminRequest $request, string $emailId)
    {
        $email  = Email::findOrFail($emailId);
        $data   = $request->validated();

        $email->recipients()->attach($data['admin_ids'], ['type' => $data['type']]);

        return response()->json([
            'message' => __('crud.created', ['name' => 'Email Admin']),
            'redirect' => route('admin.cms.emails.edit', $email->id)
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
    public function destroy(string $emailId, string $id)
    {
        $email = Email::findOrFail($emailId);

        $email->recipients()->detach($id);

        return response()->json([
            'message' => __('crud.deleted', ['name' => 'Email Admin']),
            'redirect' => route('admin.cms.emails.edit', $email->id)
        ]);
    }
}
