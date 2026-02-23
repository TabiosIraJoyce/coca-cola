<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Division;
use App\Models\ReceivableItem;

class Receivable extends Model
{
    use HasFactory;

    protected $table = 'receivables';

    protected $fillable = [
        'division_id',
        'customer_id',
        'report_date',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    /* ==============================
       RELATIONSHIPS
    ============================== */

    public function items()
    {
        return $this->hasMany(ReceivableItem::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
