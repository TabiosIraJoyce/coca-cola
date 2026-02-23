<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemittanceItem extends Model
{
    protected $table = 'remittance_items';

    protected $fillable = [
        'remittance_id',
        'type',        // cash / check
        'amount',
        'description',
        'bank_name',
        'account_name',
        'account_number',
        'check_date',
        'remarks',
        'denomination',
        'pcs',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'denomination' => 'decimal:2',
        'pcs' => 'integer',
        'check_date' => 'date',
    ];

    public function remittance()
    {
        return $this->belongsTo(Remittance::class);
    }

}
