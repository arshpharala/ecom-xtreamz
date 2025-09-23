<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Catalog\Vendor;

class VendorPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Vendor', 'View List');
    }

    public function view(Admin $admin, Vendor $vendor): bool
    {
        return $admin->has_permission('Vendor', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Vendor', 'Create');
    }

    public function update(Admin $admin, Vendor $vendor): bool
    {
        return $admin->has_permission('Vendor', 'Update');
    }

    public function delete(Admin $admin, Vendor $vendor): bool
    {
        return $admin->has_permission('Vendor', 'Delete');
    }

    public function restore(Admin $admin, Vendor $vendor): bool
    {
        return $admin->has_permission('Vendor', 'Restore');
    }
}
