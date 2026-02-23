<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CutoffPeriod extends Model
{
    protected $fillable = [
        'period_number',
        'start_date',
        'end_date',
        'rt_days'
    ];

    public $timestamps = true;

    /**
     * Backward-compat alias used by legacy consolidated dashboard code.
     */
    public function getPeriodNoAttribute(): int
    {
        return (int) ($this->attributes['period_number'] ?? 0);
    }
}
