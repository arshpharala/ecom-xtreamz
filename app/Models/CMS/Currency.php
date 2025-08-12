<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'symbol', 'symbol_html', 'exchange_rate', 'decimal', 'group_separator', 'decimal_separator', 'currency_position', 'is_default'];
}
