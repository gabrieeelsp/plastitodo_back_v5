<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;

    public $timesatamps = false;

    public function comboitems()
    {
        return $this->hasMany(Comboitem::class);
    }

    public function getComboitem_from_saleproduct($saleproduct_id)
    {
        foreach ( $this->comboitems as $comboitem ) {
            if ( $comboitem->hasSaleproduct($saleproduct_id) ) {
                return $comboitem;
            }
        }
        return null;
    }

    public function getIvaaliquot()
    {
        return $this->comboitems->first()->saleproducts->first()->stockproduct->ivaaliquot;
    }

    public function getPrecioMin()
    {
        $precio = 0;
        foreach($this->comboitems as $comboitem){
            $precio = round($precio + $comboitem->getPrecioMin(), 4, PHP_ROUND_HALF_UP);
        }
        return round($precio * round(1 - round($this->descuento / 100, 4, PHP_ROUND_HALF_UP), 8, PHP_ROUND_HALF_UP), $this->precision, PHP_ROUND_HALF_UP);
    }
}
