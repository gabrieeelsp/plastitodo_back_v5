<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordercombosaleproduct extends Model
{
    use HasFactory;

    public $timestapms = false;

    public function ordercomboitem()
    {
        return $this->belongsTo(Ordercomboitem::class);
    }

    public function saleproduct()
    {
        return $this->hasMany(Saleproducts::class);
    }
}
