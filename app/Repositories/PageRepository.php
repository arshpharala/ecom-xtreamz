<?php

namespace App\Repositories;

use App\Models\CMS\Page;

class PageRepository
{
    public function findBySlug(string $slug)
    {
        return Page::withJoins()
            ->withSelection()
            ->active()
            ->whereSlug($slug)->first();
    }

    public function findOrFailBySlug(string $slug)
    {
        if (!$slug) {
            abort(404);
        }

        $page = $this->findBySlug($slug);
        if (!$page) {
            abort(404);
        }

        return $page;
    }
}
