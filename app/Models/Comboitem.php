<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comboitem extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function saleproducts()
    {
        return $this->belongsToMany(Saleproduct::class);
    }

    public function hasSaleproduct($saleproduct_id)
    {
        foreach($this->saleproducts as $saleproduct){
            if($saleproduct->id == $saleproduct_id){
                return true;
            }
        }
        return false;
    }

    public function getPrecioMin()
    {
        $max_precio = 0;
        foreach($this->saleproducts as $saleproduct){
            if($saleproduct->getPrecioMin() > $max_precio){
                $max_precio = $saleproduct->getPrecioMin();
            }
        }
        return round($max_precio * $this->cantidad, 4, PHP_ROUND_HALF_UP);
    }
}
