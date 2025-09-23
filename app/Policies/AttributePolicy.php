<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Catalog\Attribute;

class AttributePolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Attribute', 'View List');
    }

    public function view(Admin $admin, Attribute $attribute): bool
    {
        return $admin->has_permission('Attribute', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Attribute', 'Create');
    }

    public function update(Admin $admin, Attribute $attribute): bool
    {
        return $admin->has_permission('Attribute', 'Update');
    }

    public function delete(Admin $admin, Attribute $attribute): bool
    {
        return $admin->has_permission('Attribute', 'Delete');
    }

    public function restore(Admin $admin, Attribute $attribute): bool
    {
        return $admin->has_permission('Attribute', 'Restore');
    }

    public function bulk(Admin $admin): bool
    {
        return $admin->has_permission('Attribute', 'Bulk');
    }
}
