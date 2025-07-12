<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Models\CMS\Page;
use App\Models\Seo\Meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

            $locale = app()->getLocale();

            $query = Page::select('pages.id', 'pages.slug', 'pages.is_active', 'pages.created_at', 'pages.updated_at', 'page_translations.title')
                ->leftJoin('page_translations', function ($join) use ($locale) {
                    $join->on('page_translations.page_id', 'pages.id')->where('locale', $locale);
                })
                ->groupBy('pages.id');

            return DataTables::of($query)

                ->addColumn('action', function ($row) {
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
        $page = new Page();
        $data['page'] = $page;
        return view('theme.adminlte.cms.pages.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'slug' => 'required|string|unique:pages,slug',
            'position' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'title' => 'required|array',
            'title.*' => 'required|string|max:255',
            'content' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $page = Page::create([
                'slug'      => $data['slug'],
                'position'  => $data['position'] ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            foreach (active_locals() as $locale) {
                $page->translations()->create([
                    'locale'  => $locale,
                    'title'   => $data['title'][$locale] ?? '',
                    'content' => $data['content'][$locale] ?? '',
                ]);
            }

            Meta::store($request, $page);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }


        return response()->json([
            'message' => 'Page created successfully.',
            'redirect' => route('admin.cms.pages.index')
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
        $page = Page::with('translations')->findOrFail($id);

        $data['page'] = $page;

        return view('theme.adminlte.cms.pages.edit', $data);
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
