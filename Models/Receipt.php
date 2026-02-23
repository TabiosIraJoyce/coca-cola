<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ReceiptItem;

class Receipt extends Model
{
    protected $fillable = [
        'division_id',
        'report_date',
        'route',
        'leadman',
    ];

    public function items()
    {
        return $this->hasMany(ReceiptItem::class);
    }

    public function division()
{
    return $this->belongsTo(Division::class, 'division_id');
}
}
