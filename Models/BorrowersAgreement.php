<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowersAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_name',   // Store name directly instead of borrower_id
        'division_id',
        'agreement_number',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
