<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\CMS\Currency;

class CurrencyPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Currency', 'View List');
    }

    public function view(Admin $admin, Currency $currency): bool
    {
        return $admin->has_permission('Currency', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Currency', 'Create');
    }

    public function update(Admin $admin, Currency $currency): bool
    {
        return $admin->has_permission('Currency', 'Update');
    }

    public function delete(Admin $admin, Currency $currency): bool
    {
        return $admin->has_permission('Currency', 'Delete');
    }

    public function restore(Admin $admin, Currency $currency): bool
    {
        return $admin->has_permission('Currency', 'Restore');
    }
}
