<?php

namespace App\Repositories;

use App\Models\CMS\Currency;
use Prettus\Repository\Eloquent\BaseRepository;

class CurrencyRepository extends BaseRepository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Currency::class;
    }

    /**
     * Create.
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        $currency = parent::create($attributes);

        return $currency;
    }

    /**
     * Update.
     *
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        $currency = parent::update($attributes, $id);

        return $currency;
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
