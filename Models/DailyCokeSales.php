<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyCokeSales extends Model
{
    protected $table = 'daily_coke_sales';

    protected $fillable = [
        'period_id',
        'branch',
        'movement_date',
        'size',
        'product',
        'core_cases',
        'core_ucs',
        'iws_cases',
        'iws_ucs',
        'total_ucs',
        'srp',
        'total_sales',
    ];

    public $timestamps = true;
}
