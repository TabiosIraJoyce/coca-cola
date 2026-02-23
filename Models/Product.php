<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category',
        'pack_size',
        'product_name',
        'srp',
        'status',
        'unit_ml',
        'bottles_per_case',
        'ucs',
    ];

    protected $casts = [
        'srp' => 'float',
        'unit_ml' => 'float',
        'bottles_per_case' => 'integer',
        'ucs' => 'float',
    ];

    public function getIwsUcsAttribute()
    {
        if (array_key_exists('iws_ucs', $this->attributes)) {
            return (float) ($this->attributes['iws_ucs'] ?? 0);
        }

        return (float) ($this->ucs ?? 0);
    }
}
