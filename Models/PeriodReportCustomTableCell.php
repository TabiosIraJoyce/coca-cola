<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodReportCustomTableCell extends Model
{
    protected $table = 'period_report_custom_table_cells';

    protected $fillable = [
        'custom_table_id',
        'row_index',
        'column_index',
        'value',
    ];

    protected $casts = [
        'custom_table_id' => 'integer',
        'row_index' => 'integer',
        'column_index' => 'integer',
        'value' => 'float',
    ];

    public function table()
    {
        return $this->belongsTo(PeriodReportCustomTable::class, 'custom_table_id');
    }
}

