<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidatedRemittance extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'date',
        'validated_amount',
        'validated_overage',
        'validated_shortage',
        'account_number',
        'control_number',
        'remarks'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // A remittance belongs to a division
   public function division()
{
    return $this->belongsTo(Division::class);
}

    // A remittance has many receipts
    public function receipts()
    {
        return $this->hasMany(ValidatedRemittanceReceipt::class);
    }

    public function getTotalRemittanceAttribute()
    {
    return ($this->cash_remittance ?? 0) + ($this->check_remittance ?? 0);
    }

}
