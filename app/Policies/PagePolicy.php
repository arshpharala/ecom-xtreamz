<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\CMS\Page;

class PagePolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Page', 'View List');
    }

    public function view(Admin $admin, Page $page): bool
    {
        return $admin->has_permission('Page', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Page', 'Create');
    }

    public function update(Admin $admin, Page $page): bool
    {
        return $admin->has_permission('Page', 'Update');
    }

    public function delete(Admin $admin, Page $page): bool
    {
        return $admin->has_permission('Page', 'Delete');
    }

    public function restore(Admin $admin, Page $page): bool
    {
        return $admin->has_permission('Page', 'Restore');
    }
}
