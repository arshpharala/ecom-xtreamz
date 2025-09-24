<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\CMS\Locale;

class LocalePolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Locale', 'View List');
    }

    public function view(Admin $admin, Locale $locale): bool
    {
        return $admin->has_permission('Locale', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Locale', 'Create');
    }

    public function update(Admin $admin, Locale $locale): bool
    {
        return $admin->has_permission('Locale', 'Update');
    }

    public function delete(Admin $admin, Locale $locale): bool
    {
        return $admin->has_permission('Locale', 'Delete');
    }

    public function restore(Admin $admin, Locale $locale): bool
    {
        return $admin->has_permission('Locale', 'Restore');
    }
}
