<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\CMS\Email;

class EmailPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Email', 'View List');
    }

    public function view(Admin $admin, Email $email): bool
    {
        return $admin->has_permission('Email', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Email', 'Create');
    }

    public function update(Admin $admin, Email $email): bool
    {
        return $admin->has_permission('Email', 'Update');
    }

    public function delete(Admin $admin, Email $email): bool
    {
        return $admin->has_permission('Email', 'Delete');
    }

    public function restore(Admin $admin, Email $email): bool
    {
        return $admin->has_permission('Email', 'Restore');
    }
}
