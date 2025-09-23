<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Inventory\InventorySource;

class InventorySourcePolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('InventorySource', 'View List');
    }

    public function view(Admin $admin, InventorySource $source): bool
    {
        return $admin->has_permission('InventorySource', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('InventorySource', 'Create');
    }

    public function update(Admin $admin, InventorySource $source): bool
    {
        return $admin->has_permission('InventorySource', 'Update');
    }

    public function delete(Admin $admin, InventorySource $source): bool
    {
        return $admin->has_permission('InventorySource', 'Delete');
    }
}
