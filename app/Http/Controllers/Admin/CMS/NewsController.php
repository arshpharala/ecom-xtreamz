<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Models\CMS\News;
use App\Models\Seo\Meta;
use Illuminate\Http\Request;
use App\Models\Catalog\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateNewsRequest;
use Yajra\DataTables\Facades\DataTables;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
                    // $restoreUrl = route('admin.cms.news.restore', $row->id);
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
        $categories = Category::with('translation')->get();

        $data['categories'] = $categories;

        return view('theme.adminlte.cms.news.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $data['image']         = $request->hasFile('image') ? $request->file('image')->store('news', 'public') : null;
            $data['thumbnail']         = $request->hasFile('thumbnail') ? $request->file('thumbnail')->store('news', 'public') : null;
            $data['is_active']      = $request->boolean('is_active');
            $data['is_featured']    = $request->boolean('is_featured');
            $data['is_guide']       = $request->boolean('is_guide');
            $data['created_by']     = auth('admin')->user()->id;

            $news = News::create($data);

            foreach (active_locals() as $locale) {
                $news->translations()->create([
                    'locale'            => $locale,
                    'title'             => $request->input("title.$locale"),
                    'into'              => $request->input("into.$locale"),
                    'description'       => $request->input("description.$locale"),
                ]);
            }

            Meta::store($request, $news);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return response()->json(['message' => 'News created successfully.', 'redirect' => route('admin.cms.news.index')]);
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
        $news = News::with('translations')->findOrFail($id);
        $categories = Category::with('translation')->get();

        return view('theme.adminlte.cms.news.edit', compact('news', 'categories'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $news = News::findOrFail($id);
            $data = $request->validated();

            if ($request->hasFile('image')) {
                if ($news->image) {
                    Storage::disk('public')->delete($news->image);
                }
                $data['image'] = $request->file('image')->store('news', 'public');
            }

            if ($request->hasFile('thumbnail')) {
                if ($news->thumbnail) {
                    Storage::disk('public')->delete($news->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
            }

            $data['is_active']      = $request->boolean('is_active');
            $data['is_featured']    = $request->boolean('is_featured');
            $data['is_guide']       = $request->boolean('is_guide');

            $news->update($data);

            foreach (active_locals() as $locale) {
                $news->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'title'             => $request->input("title.$locale"),
                        'intro' => $request->input("intro.$locale"),
                        'description'           => $request->input("description.$locale"),
                    ]
                );
            }

            Meta::store($request, $news);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return response()->json(['message' => 'News updated successfully.', 'redirect' => route('admin.cms.news.index')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
