<?php

namespace App\Models\Cart;

use App\Models\User;
use App\Models\CMS\Area;
use App\Models\CMS\City;
use App\Models\CMS\Country;
use App\Models\CMS\Province;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Html;

class BillingAddress extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'province', 'city', 'area', 'address', 'landmark'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
