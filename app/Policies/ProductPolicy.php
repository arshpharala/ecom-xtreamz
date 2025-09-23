<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Catalog\Product;

class ProductPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Product', 'View List');
    }

    public function view(Admin $admin, Product $product): bool
    {
        return $admin->has_permission('Product', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Product', 'Create');
    }

    public function update(Admin $admin, Product $product): bool
    {
        return $admin->has_permission('Product', 'Update');
    }

    public function delete(Admin $admin, Product $product): bool
    {
        return $admin->has_permission('Product', 'Delete');
    }

    public function restore(Admin $admin, Product $product): bool
    {
        return $admin->has_permission('Product', 'Restore');
    }

    public function bulk(Admin $admin): bool
    {
        return $admin->has_permission('Product', 'Bulk');
    }
}
