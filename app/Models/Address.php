<?php

namespace App\Models;

use App\Models\CMS\Area;
use App\Models\CMS\City;
use App\Models\CMS\Country;
use App\Models\CMS\Province;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'country_id', 'province_id', 'city_id', 'area_id', 'address', 'landmark'];


    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function render(): mixed
    {
        return "<div>
                    {$this->name}, {$this->phone}
                    <br />
                    {$this->address},
                    {$this->area->name},
                    {$this->area->landmark},
                    <br />
                    {$this->city->name},
                    {$this->province->name},
                    {$this->country->name }
        </div>";
    }
}
