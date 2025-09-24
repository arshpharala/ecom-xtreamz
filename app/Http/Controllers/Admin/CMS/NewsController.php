<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use App\Models\Catalog\Category;
use App\Models\CMS\News;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NewsController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', News::class);

        if ($request->ajax()) {
            $locale = app()->getLocale();

            $query = News::leftJoin(
                'news_translations',
                function ($join) use ($locale) {
                    $join->on('news.id', '=', 'news_translations.news_id')
                        ->where('news_translations.locale', '=', $locale);
                }
            )
                ->select(
                    'news.id',
                    'news.image',
                    'news.position',
                    'news.is_active',
                    'news.author',
                    'news.created_at',
                    'news_translations.title'
                );

            return DataTables::of($query)
                ->editColumn('image', function ($row) {
                    return $row->image
                        ? '<img src="' . asset('storage/' . $row->image) . '" class="img-sm">'
                        : '';
                })
                ->editColumn('is_active', fn($row) => $row->is_active ? '<span class="badge border border-success text-success">Visible</span>' : '<span class="badge border border-warning text-warning">Hidden</span>')
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.cms.news.edit', $row->id);
                    $deleteUrl = route('admin.cms.news.destroy', $row->id);
                    // $restoreUrl = route('admin.cms.testimonial.restore', $row->id);
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'row'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->rawColumns(['action', 'is_active', 'image'])
                ->make(true);
        }

        return view('theme.adminlte.cms.news.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', News::class);

        $categories = Category::with('translation')->get();

        $data['categories'] = $categories;

        return view('theme.adminlte.cms.news.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', News::class);
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
        $news = News::findOrFail($id);

        $this->authorize('update', $news);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $news = News::findOrFail($id);

        $this->authorize('update', $news);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $news = News::findOrFail($id);

        $this->authorize('delete', $news);

        $news->delete();

        return response()->json([
            'message' => __('crud.deleted', ['name' => 'News']),
            'redirect' => route('admin.cms.news.index')
        ]);
    }
}
