<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInputItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_input_id',
        'field_label',
        'field_type',
        'value',
    ];

    public function salesInput()
    {
        return $this->belongsTo(SalesInput::class);
    }
}
