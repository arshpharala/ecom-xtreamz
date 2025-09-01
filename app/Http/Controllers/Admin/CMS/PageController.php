<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Models\CMS\Page;
use App\Models\CMS\PageSection;
use App\Models\Seo\Meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CMS\PageSectionItem;
use App\Models\CMS\PageSectionTranslation;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
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

    public function create()
    {
        $page = new Page();
        $data['page'] = $page;
        return view('theme.adminlte.cms.pages.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slug' => 'required|string|unique:pages,slug',
            'position' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:3072',
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

            if ($request->hasFile('banner')) {
                $page->banner = $request->file('banner')->store('pages', 'public');
                $page->save();
            }

            foreach (active_locals() as $locale) {
                $page->translations()->create([
                    'locale'  => $locale,
                    'title'   => $data['title'][$locale] ?? '',
                    'content' => $data['content'][$locale] ?? '',
                ]);
            }

            Meta::store($request, $page);

            DB::commit();

            return response()->json([
                'message' => 'Page created successfully.',
                'redirect' => route('admin.cms.pages.index')
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public function show(string $id) {}

    public function edit($id)
    {
        $page = Page::with([
            'translations',
            'sections.translations'
        ])->findOrFail($id);

        $data['page'] = $page;

        return view('theme.adminlte.cms.pages.edit', $data);
    }


    public function update(Request $request, string $id)
    {
        $page = Page::with('sections.translations')->findOrFail($id);

        $data = $request->validate([
            'slug' => 'required|string|unique:pages,slug,' . $page->id,
            'position' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:3072',
            'title' => 'required|array',
            'title.*' => 'required|string|max:255',
            'content' => 'nullable|array',
            'sections' => 'nullable|array',
            'sections.*.type' => 'required|string|max:255',
            'sections.*.heading' => 'nullable|array',
            'sections.*.content' => 'nullable|array',
            'sections.*.image' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:3072',
        ]);

        DB::beginTransaction();

        try {
            $page->update([
                'slug'      => $data['slug'],
                'position'  => $data['position'] ?? 0,
                'is_active' => $request->has('is_active'),
            ]);

            if ($request->hasFile('banner')) {
                if ($page->banner && Storage::disk('public')->exists($page->banner)) {
                    Storage::disk('public')->delete($page->banner);
                }

                $page->banner = $request->file('banner')->store('pages', 'public');
                $page->save();
            }

            foreach (active_locals() as $locale) {
                $page->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'title'   => $data['title'][$locale] ?? '',
                        'content' => $data['content'][$locale] ?? '',
                    ]
                );
            }

            Meta::store($request, $page);

            if ($request->has('sections')) {
                $submittedIds = [];

                foreach ($request->sections as $index => $sectionData) {
                    $sectionId = $sectionData['id'] ?? null;

                    // Create or update
                    if ($sectionId) {
                        $section = PageSection::findOrFail($sectionId);
                    } else {
                        $section = new PageSection();
                        $section->page_id = $page->id;
                    }

                    $section->type      = $sectionData['type'];
                    $section->position  = $index;
                    $section->is_active = true;

                    // Image handling
                    if ($request->hasFile("sections.$index.image")) {
                        if ($section->image && Storage::disk('public')->exists($section->image)) {
                            Storage::disk('public')->delete($section->image);
                        }
                        $section->image = $request->file("sections.$index.image")->store('sections', 'public');
                    }

                    $section->save();
                    $submittedIds[] = $section->id;

                    foreach (active_locals() as $locale) {
                        $section->translations()->updateOrCreate(
                            ['locale' => $locale],
                            [
                                'heading' => $sectionData['heading'][$locale] ?? '',
                                'content' => $sectionData['content'][$locale] ?? '',
                            ]
                        );
                    }
                }

                $page->sections()
                    ->whereNotIn('id', $submittedIds)
                    ->each(function ($section) {
                        if ($section->image && Storage::disk('public')->exists($section->image)) {
                            Storage::disk('public')->delete($section->image);
                        }

                        $section->translations()->delete();
                        $section->delete();
                    });
            }


            DB::commit();

            return response()->json([
                'message' => 'Page updated successfully.',
                'redirect' => route('admin.cms.pages.index')
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public function destroy(string $id) {}

    public function restore(string $id) {}
}
