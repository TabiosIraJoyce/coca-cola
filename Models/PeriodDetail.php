<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_report_id','row_index','data','created_by','updated_by'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function report()
    {
        return $this->belongsTo(PeriodReport::class, 'period_report_id');
    }
}
