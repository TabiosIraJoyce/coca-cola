<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Division;
use App\Models\BorrowerItem;

class Borrower extends Model
{
    protected $fillable = [
        'division_id',
        'report_date'
    ];

    public function items()
    {
        return $this->hasMany(BorrowerItem::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
