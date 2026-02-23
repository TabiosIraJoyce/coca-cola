<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_line_id',
        'field_label',
        'field_type',
        'is_required',
        'field_order',
    ];

    public function businessLine()
    {
        return $this->belongsTo(BusinessLine::class);
    }
}
