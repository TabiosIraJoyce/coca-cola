<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ReceivableItem;

class Customer extends Model
{
    protected $table = 'customers';

    protected $guarded = [];

    public $timestamps = true;

    // ✅ DIRECT RELATION TO RECEIVABLE ITEMS
    public function receivableItems()
    {
        return $this->hasMany(ReceivableItem::class);
    }
}
