<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivableItem extends Model
{
    protected $table = 'receivable_items';

    protected $fillable = [
        'receivable_id',
        'type',
        'si_number',
        'reference_no',
        'customer_id',  
        'customer_name',
        'collection_type',
        'description',
        'remarks',
        'terms',
        'terms_days',
        'due_date',
        'amount',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount'   => 'decimal:2',
    ];

    public function receivable()
    {
        return $this->belongsTo(Receivable::class);
    }

    // ✅ ADDED — allows $item->division
    public function division()
    {
        return $this->hasOneThrough(
            Division::class,
            Receivable::class,
            'id',
            'id',
            'receivable_id',
            'division_id'
        );
    }

    public function getPaymentStatusAttribute()
    {
        if (!$this->due_date) return 'no_terms';

        $today = Carbon::today();
        $due = Carbon::parse($this->due_date);

        if ($today->gt($due)) return 'overdue';
        if ($today->eq($due)) return 'due_today';

        return 'upcoming';
    }
    public function customer()
{
    return $this->belongsTo(Customer::class, 'customer_id');
}


}
