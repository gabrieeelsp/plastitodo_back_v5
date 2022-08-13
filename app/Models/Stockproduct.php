<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stockproduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'costo',
        'is_stock_unitario_variable',
        'stock_aproximado_unidad',
        'ivaaliquot_id',
    ];

    public $timestamps = false;

    public function ivaaliquot()
    {
        return $this->belongsTo(Ivaaliquot::class);
    }

    public function stocksucursals ()
    {
        return $this->hasMany(Stocksucursal::class);
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
        return 0;
    }

    public function getStockTotal() {
        $stock = 0;
        foreach ( $this->stockSucursales as $stockSucursal) {
            $stock = $stock + $stockSucursal->stock;
        }
        return round($stock, 4, PHP_ROUND_HALF_UP);
    }

    public function saleproducts() 
    {
        return $this->hasMany(Saleproduct::class)->orderBy('name');
    }
    
    public function stockproductgroup() 
    {
        return $this->belongsTo(Stockproductgroup::class);
    }

    public function purchaseproducts ()
    {
        return $this->hasMany(Purchaseproduct::class);
    }

    public function familia() 
    {
        return $this->belongsTo(Familia::class);
    }
}
