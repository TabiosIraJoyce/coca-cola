<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodReportInventory extends Model
{
    protected $table = 'period_report_inventories';

    protected $fillable = [
        'period_report_id',
        'pack',
        'product',

        'srp',
        'actual_inv',
        'ads',

        'booking',
        'deliveries',

        'routing_days_p5',
        'routing_days_7',
    ];

    public function report()
    {
        return $this->belongsTo(PeriodReport::class);
    }
}
