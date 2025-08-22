<?php

namespace App\Http\Controllers\Web\V2;

use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    function search(Request $request)
    {

        $slug           = request()->segment(1);
        $page           = (new PageRepository())->findOrFailBySlug($slug);

        $searchQuery    = $request->input('q', '');

        $data['searchQuery'] = $searchQuery;

        $data['page'] = $page;


        return view('theme.xtremez.products.search', $data);
    }
}
