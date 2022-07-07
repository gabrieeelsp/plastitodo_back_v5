<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stockproduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function ivaaliquot()
    {
        return $this->belongsTo(Ivaaliquot::class);
    }

    public function stockSucursales()
    {
        return $this->hasMany(Stocksucursal::class);
    }

    public function getStockSucursal($sucursal_id) {
        
        foreach ( $this->stockSucursales as $stockSucursal) {
            if ( $sucursal_id == $stockSucursal->sucursal_id ) {
                return $stockSucursal->stock;
            }
            
        }
    }
}
