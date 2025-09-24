<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\CMS\Testimonial;

class TestimonialPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->has_permission('Testimonial', 'View List');
    }

    public function view(Admin $admin, Testimonial $testimonial): bool
    {
        return $admin->has_permission('Testimonial', 'View');
    }

    public function create(Admin $admin): bool
    {
        return $admin->has_permission('Testimonial', 'Create');
    }

    public function update(Admin $admin, Testimonial $testimonial): bool
    {
        return $admin->has_permission('Testimonial', 'Update');
    }

    public function delete(Admin $admin, Testimonial $testimonial): bool
    {
        return $admin->has_permission('Testimonial', 'Delete');
    }

    public function restore(Admin $admin, Testimonial $testimonial): bool
    {
        return $admin->has_permission('Testimonial', 'Restore');
    }
}
