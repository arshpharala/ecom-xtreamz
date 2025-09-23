<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Cart\Coupon;

class CouponPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Coupon', 'View List');
    }

    public function view(Admin $admin, Coupon $coupon): bool
    {
        return $admin->has_permission('Coupon', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Coupon', 'Create');
    }

    public function update(Admin $admin, Coupon $coupon): bool
    {
        return $admin->has_permission('Coupon', 'Update');
    }

    public function delete(Admin $admin, Coupon $coupon): bool
    {
        return $admin->has_permission('Coupon', 'Delete');
    }
}
