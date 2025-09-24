<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\CMS\News;

class NewsPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('News', 'View List');
    }

    public function view(Admin $admin, News $news): bool
    {
        return $admin->has_permission('News', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('News', 'Create');
    }

    public function update(Admin $admin, News $news): bool
    {
        return $admin->has_permission('News', 'Update');
    }

    public function delete(Admin $admin, News $news): bool
    {
        return $admin->has_permission('News', 'Delete');
    }

    public function restore(Admin $admin, News $news): bool
    {
        return $admin->has_permission('News', 'Restore');
    }
}
