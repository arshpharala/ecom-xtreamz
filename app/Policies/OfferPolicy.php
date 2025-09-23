<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Catalog\Offer;

class OfferPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Offer', 'View List');
    }

    public function view(Admin $admin, Offer $offer): bool
    {
        return $admin->has_permission('Offer', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Offer', 'Create');
    }

    public function update(Admin $admin, Offer $offer): bool
    {
        return $admin->has_permission('Offer', 'Update');
    }

    public function delete(Admin $admin, Offer $offer): bool
    {
        return $admin->has_permission('Offer', 'Delete');
    }

    public function restore(Admin $admin, Offer $offer): bool
    {
        return $admin->has_permission('Offer', 'Restore');
    }
}
