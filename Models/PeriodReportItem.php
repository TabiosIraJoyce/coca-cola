<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PeriodReport;

class PeriodReportItem extends Model
{
    protected $table = 'period_report_items';

    protected $fillable = [
    'period_report_id',
    'pack',
    'product',
    'category',
    'core_pcs',
    'core_ucs',
    'core_total_ucs',
    'iws_pcs',
    'iws_ucs',
    'iws_total_ucs',
];


    public function report()
    {
        return $this->belongsTo(PeriodReport::class, 'period_report_id');
    }
}
