<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\CMS\PaymentGateway;
use App\Services\StripeService;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * Create user
     */
    public function create(array $attributes)
    {
        $user = parent::create($attributes);

        /**
         * ✅ Create Stripe customer ONLY if Stripe gateway is active
         */
        $stripeEnabled = PaymentGateway::where('gateway', 'stripe')
            ->where('is_active', 1)
            ->exists();

        if ($stripeEnabled) {
            // Lazy-load StripeService only when needed
            (new StripeService())->ensureStripeCustomer($user);
            $user->refresh();
        }

        return $user;
    }

    /**
     * Update user
     */
    public function update(array $attributes, $id)
    {
        return parent::update($attributes, $id);
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        if ($this->model->count() == 1) {
            return false;
        }

        return (bool) $this->model->destroy($id);
    }
}
