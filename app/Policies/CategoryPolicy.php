<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Admin;
use App\Models\Catalog\Category;

class CategoryPolicy
{
    /**
     * View all categories.
     */
    public function viewAny(Admin|User $admin): bool
    {
        return $admin->has_permission('Category', 'View List');
    }

    /**
     * View a single category.
     */
    public function view(Admin|User $admin, Category $category): bool
    {
        return $admin->has_permission('Category', 'View');
    }

    /**
     * Create new category.
     */
    public function create(Admin|User $admin): bool
    {
        return $admin->has_permission('Category', 'Create');
    }

    /**
     * Update a category.
     */
    public function update(Admin|User $admin, Category $category): bool
    {
        return $admin->has_permission('Category', 'Update');
    }

    /**
     * Delete a category.
     */
    public function delete(Admin|User $admin, Category $category): bool
    {
        return $admin->has_permission('Category', 'Delete');
    }

    /**
     * Restore a category.
     */
    public function restore(Admin|User $admin, Category $category): bool
    {
        return $admin->has_permission('Category', 'Restore');
    }

    /**
     * Bulk actions: delete or restore multiple categories.
     */
    public function bulk(Admin|User $admin): bool
    {
        return $admin->has_permission('Category', 'Bulk');
    }
}
