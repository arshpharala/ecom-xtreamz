<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Catalog\Brand;

class BrandPolicy
{
    /**
     * View all brands.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Brand', 'View List');
    }

    /**
     * View a single brand.
     */
    public function view(Admin $admin, Brand $brand): bool
    {
        return $admin->has_permission('Brand', 'View');
    }

    /**
     * Create a new brand.
     */
    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Brand', 'Create');
    }

    /**
     * Update a brand.
     */
    public function update(Admin $admin, Brand $brand): bool
    {
        return $admin->has_permission('Brand', 'Update');
    }

    /**
     * Delete a brand.
     */
    public function delete(Admin $admin, Brand $brand): bool
    {
        return $admin->has_permission('Brand', 'Delete');
    }

    /**
     * Restore a brand.
     */
    public function restore(Admin $admin, Brand $brand): bool
    {
        return $admin->has_permission('Brand', 'Restore');
    }
}
