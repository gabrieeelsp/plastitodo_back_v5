<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocksucursal extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function stockproduct()
    {
        return $this->belongsTo(Stockproduct::class);
    }
    
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
