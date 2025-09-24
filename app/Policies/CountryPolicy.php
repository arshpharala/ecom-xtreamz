<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\CMS\Country;

class CountryPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Country', 'View List');
    }

    public function view(Admin $admin, Country $country): bool
    {
        return $admin->has_permission('Country', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Country', 'Create');
    }

    public function update(Admin $admin, Country $country): bool
    {
        return $admin->has_permission('Country', 'Update');
    }

    public function delete(Admin $admin, Country $country): bool
    {
        return $admin->has_permission('Country', 'Delete');
    }

    public function restore(Admin $admin, Country $country): bool
    {
        return $admin->has_permission('Country', 'Restore');
    }
}
