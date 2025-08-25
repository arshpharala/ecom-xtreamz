<?php

namespace App\Http\Controllers\Admin\CMS;

use Illuminate\Http\Request;
use App\Models\CMS\Testimonial;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Models\CMS\TestimonialTranslation;
use App\Http\Requests\StoreTestimonialRequest;
use App\Http\Requests\UpdateTestimonialRequest;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $locale = app()->getLocale();

            $query = Testimonial::leftJoin(
                'testimonial_translations',
                function ($join) use ($locale) {
                    $join->on('testimonials.id', '=', 'testimonial_translations.testimonial_id')
                        ->where('testimonial_translations.locale', '=', $locale);
                }
            )
                ->select(
                    'testimonials.id',
                    'testimonials.image',
                    'testimonials.position',
                    'testimonials.is_active',
                    'testimonials.rating',
                    'testimonials.created_at',
                    'testimonial_translations.name'
                );

            return DataTables::of($query)
                ->editColumn('image', function ($row) {
                    return $row->image
                        ? '<img src="' . asset('storage/' . $row->image) . '" class="img-sm">'
                        : '';
                })
                ->editColumn('is_active', fn($row) => $row->is_active ? '<span class="badge border border-success text-success">Visible</span>' : '<span class="badge border border-warning text-warning">Hidden</span>')
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.cms.testimonials.edit', $row->id);
                    $deleteUrl = route('admin.cms.testimonials.destroy', $row->id);
                    // $restoreUrl = route('admin.cms.testimonial.restore', $row->id);
                    return view('theme.adminlte.components._table-actions', compact('editUrl', 'deleteUrl', 'row'))->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at?->format('d-M-Y  h:m A');
                })
                ->rawColumns(['action', 'is_active', 'image'])
                ->make(true);
        }

        return view('theme.adminlte.cms.testimonials.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('theme.adminlte.cms.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTestimonialRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('testimonials', 'public');
        }

        if ($request->hasFile('company_logo')) {
            $validated['company_logo'] = $request->file('company_logo')->store('testimonials', 'public');
        }

        try {
            $testimonial = Testimonial::create([
                'rating'        => $validated['rating'] ?? 5,
                'is_active'     => $validated['is_active'] ?? false,
                'position'      => $validated['position'] ?? 0,
                'image'         => $validated['image'] ?? null,
                'company_logo'  => $validated['company_logo'] ?? null,
                'company_name'  => $validated['company_name'] ?? null,
                'designation'   => $validated['designation'] ?? null,
            ]);


            foreach ($validated['name'] as $locale => $name) {
                TestimonialTranslation::create([
                    'testimonial_id'    => $testimonial->id,
                    'locale'            => $locale,
                    'name'              => $name,
                    'description'       => $validated['description'][$locale] ?? null,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }


        return response()->json([
            'message'   => __('crud.created', ['name' => 'Testimonial']),
            'redirect' => route('admin.cms.testimonials.index')
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
        $testimonial            = Testimonial::findOrFail($id);
        $data['testimonial']    = $testimonial;

        return view('theme.adminlte.cms.testimonials.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTestimonialRequest $request, string $id)
    {
        $testimonial            = Testimonial::findOrFail($id);

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $data = [
                'rating'        => $validated['rating'] ?? 5,
                'is_active'     => $validated['is_active'] ?? false,
                'position'      => $validated['position'] ?? 0,
                'designation'   => $validated['designation'] ?? 0,
                'company_name'  => $validated['company_name'] ?? null,
            ];


            if ($request->hasFile('image')) {

                if ($testimonial->image && Storage::disk('public')->exists($testimonial->image)) {
                    Storage::disk('public')->delete($testimonial->image);
                }

                $data['image'] = $request->file('image')->store('promos', 'public');
            }


            if ($request->hasFile('company_logo')) {

                if ($testimonial->company_logo && Storage::disk('public')->exists($testimonial->company_logo)) {
                    Storage::disk('public')->delete($testimonial->company_logo);
                }

                $data['company_logo'] = $request->file('company_logo')->store('promos', 'public');
            }


            $testimonial->update($data);



            foreach ($validated['name'] as $locale => $name) {
                TestimonialTranslation::updateOrCreate(
                    ['testimonial_id' => $testimonial->id, 'locale' => $locale],
                    [
                        'name'       => $name,
                        'description' => $validated['description'][$locale] ?? null,
                    ]
                );
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }


        return response()->json([
            'message'   => __('crud.updated', ['name' => 'Testimonial']),
            'redirect' => route('admin.cms.testimonials.index')
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
