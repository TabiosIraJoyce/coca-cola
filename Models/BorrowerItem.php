<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Borrower;
use App\Models\Division;

class BorrowerItem extends Model
{
    protected $fillable = [
        'borrower_id',
        'item_type',
        'location',
        'borrowed',
        'returned'
    ];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    // ✅ ADDED — allows $item->division
    public function division()
    {
        return $this->hasOneThrough(
            Division::class,
            Borrower::class,
            'id',          // FK on borrowers table
            'id',          // FK on divisions table
            'borrower_id', // Local key on borrower_items
            'division_id'  // Local key on borrowers
        );
    }
}
