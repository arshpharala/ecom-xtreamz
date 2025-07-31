<?php

namespace App\Repositories;

use App\Models\CMS\Country;
use Prettus\Repository\Eloquent\BaseRepository;

class CountryRepository extends BaseRepository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Country::class;
    }

    /**
     * Create.
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        $country = parent::create($attributes);

        return $country;
    }

    /**
     * Update.
     *
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        $country = parent::update($attributes, $id);

        return $country;
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
