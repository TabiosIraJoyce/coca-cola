<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodReportCustomTable extends Model
{
    protected $table = 'period_report_custom_tables';

    protected $fillable = [
        'period_report_id',
        'title',
    ];

    public function report()
    {
        return $this->belongsTo(PeriodReport::class);
    }

    public function cells()
    {
        return $this->hasMany(PeriodReportCustomTableCell::class, 'custom_table_id');
    }
}
