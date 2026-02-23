<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = [
        'division_name',
        'supervisor_name',
        'oic_name',
        'division_address',
        'division_contact_number',
        'division_telephone_number'
    ];

    public function businessLine()
    {
        return $this->belongsTo(BusinessLine::class);
    }


    public function salesInputs()
    {
        return $this->hasMany(SalesInput::class);
    }

    public function validatedRemittances()
    {
        return $this->hasMany(ValidatedRemittance::class);
    }

    public function borrowersAgreements()
    {
        return $this->hasMany(BorrowersAgreement::class);
    }
}
