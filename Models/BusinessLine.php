<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessLine extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description'];
   
    // ✅ A business line has many divisions
    public function divisions()
    {
        return $this->hasMany(Division::class);
    }
}
