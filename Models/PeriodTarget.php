<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodTarget extends Model
{
    // Period targets are stored in a legacy table name.
    protected $table = 'period_targets_clean';

    protected $fillable = [
        'branch',
        'period_no',
        'core_target_sales',
        'petcsd_target_sales',
        'stills_target_sales',
        'start_date',
        'end_date',
        'is_locked',
        'sku_targets',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'sku_targets' => 'array',
    ];
}
