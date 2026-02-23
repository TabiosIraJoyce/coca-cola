<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInput extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'business_line_id',
        'date',
        'data', // JSON backup
    ];

    protected $casts = [
        'data' => 'array',
        'date' => 'date',
    ];

    // Relations
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function businessLine()
    {
        return $this->belongsTo(BusinessLine::class);
    }

    public function items()
    {
        return $this->hasMany(SalesInputItem::class);
    }
}
