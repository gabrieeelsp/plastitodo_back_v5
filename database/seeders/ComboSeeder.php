<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Comboitem;

class ComboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //--1---------------------------------------------
        DB::table('combos')->insert([
            'name' => 'Golosinas ROJA',
            'descuento' => 10,
            'precision' => -1,
        ]);

        $comboitem = new Comboitem();
        $comboitem->name = '100 Caramelos';
        $comboitem->cantidad = 1;
        $comboitem->combo_id = 1;
        $comboitem->save();
        $comboitem->saleproducts()->attach(17);
        $comboitem->save();

        $comboitem = new Comboitem();
        $comboitem->name = '50 Chupetines';
        $comboitem->cantidad = 1;
        $comboitem->combo_id = 1;
        $comboitem->save();
        $comboitem->saleproducts()->attach(5);
        $comboitem->save();

        $comboitem = new Comboitem();
        $comboitem->name = '20 Alfajores Guaymallen';
        $comboitem->cantidad = 20;
        $comboitem->combo_id = 1;
        $comboitem->save();
        $comboitem->saleproducts()->attach(1);
        $comboitem->saleproducts()->attach(3);
        $comboitem->save();
    }
}