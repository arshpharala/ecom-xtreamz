<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\CMS\Banner;

class BannerPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Banner', 'View List');
    }

    public function view(Admin $admin, Banner $banner): bool
    {
        return $admin->has_permission('Banner', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Banner', 'Create');
    }

    public function update(Admin $admin, Banner $banner): bool
    {
        return $admin->has_permission('Banner', 'Update');
    }

    public function delete(Admin $admin, Banner $banner): bool
    {
        return $admin->has_permission('Banner', 'Delete');
    }

    public function restore(Admin $admin, Banner $banner): bool
    {
        return $admin->has_permission('Banner', 'Restore');
    }
}
