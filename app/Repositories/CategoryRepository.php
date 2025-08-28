<?php

namespace App\Repositories;

use App\Models\Catalog\Category;
use Illuminate\Support\Facades\Request;

class CategoryRepository
{
    public function getCategories(int $perPage = 3)
    {
        $filters = Request::only([
            'is_new',
            'show_on_homepage',
            'parent_id',
            'product',
            'search',
            'sort_by'
        ]);

        $query = Category::visible()
            ->withJoins()
            ->withFilters($filters)
            ->withSelection()
            ->applySorting($filters['sort_by'] ?? null);

        if (Request::has('page')) {
            return $query->paginate($perPage)->through(function ($category) {
                return $this->transform($category);
            });
        }

        return $query->limit($perPage)->get()->map(function ($category) {
            return $this->transform($category);
        });
    }

    public function getHomeScreenCategories(int $perPage = 3)
    {
        Request::merge([
            'show_on_homepage' => 1,
            'product' => true
        ]);

        return $this->getCategories($perPage);
    }

    function transform($category)
    {
        $category->link       = route('products', ['category_id' => $category->id]);
        $category->image      = $category->image ? asset('storage/' . $category->image) : null;

        return $category;
    }
}
