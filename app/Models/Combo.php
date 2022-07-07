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

    public function setPrecios()
    {
        $precio_min = 0;
        $precio_may = 0;
        foreach($this->comboitems as $comboitem){
            $precio_min = round($precio + $comboitem->getPrecioMin(), 4, PHP_ROUND_HALF_UP);
            $precio_may = round($precio + $comboitem->getPrecioMay(), 4, PHP_ROUND_HALF_UP);
        }
        $this->precio_min = round($precio_min * round(1 - round($this->desc_min / 100, 4, PHP_ROUND_HALF_UP), 8, PHP_ROUND_HALF_UP), $this->precision, PHP_ROUND_HALF_UP);
        $this->precio_may = round($precio_may * round(1 - round($this->desc_may / 100, 4, PHP_ROUND_HALF_UP), 8, PHP_ROUND_HALF_UP), $this->precision, PHP_ROUND_HALF_UP);

        $this.save();
    }
}
