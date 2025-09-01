<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Models\CMS\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CMS\BannerTranslation;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $banners = Banner::withJoins()
                ->withSelection()
                ->withTrashed()
                ->groupBy('banners.id');

            return DataTables::of($banners)
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.cms.banners.edit', $row->id);
                    $deleteUrl = route('admin.cms.banners.destroy', $row->id);
                    $restoreUrl = route('admin.cms.banners.restore', $row->id);
                    $editSidebar = true;
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'restoreUrl', 'row', 'editSidebar'))->render();
                })
                ->addColumn('is_active', fn($row) => $row->deleted_at ? '<span class="badge badge-danger">Deleted</span>' : ($row->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Inactive</span>'))
                ->editColumn('position', fn($row) => $row->position)
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('theme.adminlte.cms.banners.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['banner'] = new Banner();
        $response['view'] = view('theme.adminlte.cms.banners.create', $data)->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $imagePath = $request->file('image')->store('banners', 'public');
            $bgPath = $request->file('background')->store('banners', 'public');

            $banner = Banner::create([
                'image' => $imagePath,
                'background' => $bgPath,
                'text_color' => $validated['text_color'],
                'btn_text' => $validated['btn_text'] ?? null,
                'btn_color' => $validated['btn_color'] ?? null,
                'btn_link' => $validated['btn_link'] ?? null,
                'position' => $validated['position'] ?? 0,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            foreach ($validated['title'] as $locale => $title) {
                BannerTranslation::create([
                    'banner_id' => $banner->id,
                    'locale' => $locale,
                    'title' => $title,
                    'subtitle' => $validated['subtitle'][$locale] ?? null,
                    'description' => $validated['description'][$locale] ?? null,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return response()->json([
            'message' => __('crud.created', ['name' => 'Banner']),
            'redirect' => route('admin.cms.banners.index')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banner = Banner::with('translations')->findOrFail($id);
        $data['banner'] = $banner;

        $response['view'] = view('theme.adminlte.cms.banners.edit', $data)->render();

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, string $id)
    {
        $validated = $request->validated();
        $banner = Banner::findOrFail($id);

        $data = [
            'text_color' => $validated['text_color'],
            'btn_text' => $validated['btn_text'] ?? null,
            'btn_color' => $validated['btn_color'] ?? null,
            'btn_link' => $validated['btn_link'] ?? null,
            'position' => $validated['position'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ];

        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        if ($request->hasFile('background')) {
            if ($banner->background && Storage::disk('public')->exists($banner->background)) {
                Storage::disk('public')->delete($banner->background);
            }
            $data['background'] = $request->file('background')->store('banners', 'public');
        }

        $banner->update($data);

        foreach ($validated['title'] as $locale => $title) {
            BannerTranslation::updateOrCreate(
                ['banner_id' => $banner->id, 'locale' => $locale],
                [
                    'title' => $title,
                    'subtitle' => $validated['subtitle'][$locale] ?? null,
                    'description' => $validated['description'][$locale] ?? null,
                ]
            );
        }

        return response()->json([
            'message' => __('crud.updated', ['name' => 'Banner']),
            'redirect' => route('admin.cms.banners.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::withTrashed()->findOrFail($id);
        $banner->delete();

        return response()->json([
            'message' => __('crud.deleted', ['name' => 'Banner']),
            'redirect' => route('admin.cms.banners.index')
        ]);
    }

    public function restore(string $id)
    {
        $banner = Banner::withTrashed()->findOrFail($id);
        $banner->restore();

        return response()->json([
            'message' => __('crud.restored', ['name' => 'Banner']),
        ]);
    }
}
