<?php

namespace App\Repositories;

use App\Models\Address;
use Prettus\Repository\Eloquent\BaseRepository;

class AddressRepository extends BaseRepository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Address::class;
    }

    /**
     * Create.
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        $address = parent::create($attributes);

        return $address;
    }

    /**
     * Update.
     *
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        $address = parent::update($attributes, $id);

        return $address;
    }

    /**
     * Delete.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        if ($this->model->count() == 1) {
            return false;
        }

        if ($this->model->destroy($id)) {

            return true;
        }

        return false;
    }
}
