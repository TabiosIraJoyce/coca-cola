<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'banks';

    protected $fillable = [
        'bank_name',
        'branch_name',
        'account_holder_name',
        'account_number',
        'routing_number',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /* =========================
       SCOPES
    ========================= */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
    
}
