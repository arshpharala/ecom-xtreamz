<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\CMS\Tag;

class TagPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Tag', 'View List');
    }

    public function view(Admin $admin, Tag $tag): bool
    {
        return $admin->has_permission('Tag', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Tag', 'Create');
    }

    public function update(Admin $admin, Tag $tag): bool
    {
        return $admin->has_permission('Tag', 'Update');
    }

    public function delete(Admin $admin, Tag $tag): bool
    {
        return $admin->has_permission('Tag', 'Delete');
    }

    public function restore(Admin $admin, Tag $tag): bool
    {
        return $admin->has_permission('Tag', 'Restore');
    }
}
