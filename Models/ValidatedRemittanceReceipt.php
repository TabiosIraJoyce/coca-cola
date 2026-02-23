<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidatedRemittanceReceipt extends Model
{
    use HasFactory;

protected $fillable = [
        'validated_remittance_id',
        'file_path',
    ];

    // A receipt belongs to a remittance
public function remittance()
{
    return $this->belongsTo(ValidatedRemittance::class);
}


    // ValidatedRemittanceReceipt.php
public function validatedRemittance()
{
    return $this->belongsTo(ValidatedRemittance::class);
}

// ValidatedRemittance.php
public function receipts()
{
    return $this->hasMany(ValidatedRemittanceReceipt::class);
}

public function division()
{
    return $this->belongsTo(Division::class);
}

}
