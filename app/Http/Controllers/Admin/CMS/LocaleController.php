<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Enums\TextDirection;
use App\Models\CMS\Locale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocaleRequest;
use App\Http\Requests\UpdateLocaleRequest;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class LocaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {

            $query = Locale::withTrashed();

            return DataTables::of($query)

                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.cms.locales.edit', $row->id);
                    $deleteUrl = route('admin.cms.locales.destroy', $row->id);
                    $restoreUrl = route('admin.cms.locales.restore', $row->id);
                    $editSidebar = true;
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'editSidebar', 'row'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('theme.adminlte.cms.locales.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $directions             = TextDirection::cases();

        $data['directions']     = $directions;
        $response['view']       = view('theme.adminlte.cms.locales.create', $data)->render();

        return response()->json([
            'success'   => true,
            'data'      => $response
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocaleRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('locale', 'public');
        }

        Locale::create($data);

        return response()->json([
            'message'  => 'Locale created successfully.',
            'redirect' => route('admin.cms.locales.index')
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
        $locale = Locale::findOrFail($id);

        $directions = \App\Enums\TextDirection::cases();

        $data['directions'] = $directions;
        $data['locale']      = $locale;

        $response['view'] = view('theme.adminlte.cms.locale.edit', $data)->render();

        return response()->json([
            'success' => true,
            'data'    => $response
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocaleRequest $request, string $id)
    {
        $locale = Locale::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('logo')) {

            if ($locale->logo && Storage::disk('public')->exists($locale->logo)) {
                Storage::disk('public')->delete($locale->logo);
            }
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        } else {
            unset($request['logo']);
        }

        $locale->update($data);


        return response()->json([
            'message'  => 'Locale updated successfully.',
            'redirect' => route('admin.cms.locales.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $locale = Locale::findOrFail($id);

        $locale->delete();

        return response()->json(['message' => 'Locale Deleted.']);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        $locale = Locale::onlyTrashed()->findOrFail($id);

        $locale->restore();

        return response()->json(['message' => 'Locale Restored.']);
    }
}
