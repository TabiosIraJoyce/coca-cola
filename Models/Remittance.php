<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bank;
use App\Models\Division;

class Remittance extends Model
{
    protected $table = 'remittance';

    protected $fillable = [
        'division_id',
        'report_date',
        'total_cash',
        'total_checks',
        'total_remitted',
        'remarks',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function items()
    {
        return $this->hasMany(RemittanceItem::class);
    }
}
