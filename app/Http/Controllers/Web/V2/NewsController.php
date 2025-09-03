<?php

namespace App\Http\Controllers\Web\V2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CMS\News;
use App\Repositories\PageRepository;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::where('is_active', true)
            ->orderBy('position')
            ->orderBy('created_at', 'desc')
            ->with('translation')
            ->paginate(9);

        $slug = request()->segment(1);

        $page = (new PageRepository())->findBySlug($slug);

        $data['news'] = $news;
        $data['page'] = $page;

        return view('theme.medibazaar.news.index', $data);
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
    public function store(Request $request)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
