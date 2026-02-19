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

    public function render(bool $plain = false): mixed
    {
        $landmark = filled($this->landmark) ? "{$this->landmark}," : null;

        if ($plain) {
            $parts = [
                "{$this->address},",
                $landmark,
                "{$this->city->name},",
                "{$this->province->name},",
                $this->country->name,
            ];

            return implode(' ', array_filter($parts));
        }

        $line1Parts = array_filter([
            "{$this->name}, {$this->phone}",
        ]);

        $line2Parts = array_filter([
            "{$this->address},",
            $landmark,
        ]);

        $line3Parts = array_filter([
            "{$this->city->name},",
            "{$this->province->name},",
            $this->country->name,
        ]);

        return '<div>'
            .implode(' ', $line1Parts)
            .'<br />'
            .implode(' ', $line2Parts)
            .'<br />'
            .implode(' ', $line3Parts)
            .'</div>';
    }
}
