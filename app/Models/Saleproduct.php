<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saleproduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function stockproduct()
    {
        return $this->belongsTo(Stockproduct::class);
    }

    public function getCosto()
    {   
        return round($this->stockproduct->costo * $this->relacion_venta_stock, 8, PHP_ROUND_HALF_UP);
    }

    public function getPrecioMin()
    {   
        return round($this->getCosto() * round(1 + round($this->porc_min / 100, 4, PHP_ROUND_HALF_UP), 8, PHP_ROUND_HALF_UP), 4, PHP_ROUND_HALF_UP);
    }

    public function getPrecioMay()
    {   
        return round($this->getCosto() * round(1 + round($this->porc_may / 100, 8, PHP_ROUND_HALF_UP), 8, PHP_ROUND_HALF_UP), 4, PHP_ROUND_HALF_UP);
    }
}
