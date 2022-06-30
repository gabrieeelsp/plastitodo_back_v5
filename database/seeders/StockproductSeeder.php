<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Sucursal;

class StockproductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //--1---------------------------------------------
        DB::table('stockproducts')->insert([
            'name' => 'Alfajor 38g Blanco GUAYMALLEN UNIDAD',
            'ivaaliquot_id' => 4,
            'costo' => 12.5
        ]);
        foreach(Sucursal::all() as $sucursal){
            DB::table('stocksucursals')->insert([
                'stockproduct_id' => 1,
                'sucursal_id' => $sucursal->id
            ]);
        }
        DB::table('saleproducts')->insert([ // 1
            'stockproduct_id' => 1,
            'name' => 'Alfajor 38g Blanco GUAYMALLEN UNIDAD',
            'relacion_venta_stock' => 1,
            'porc_min' => 40,
            'porc_may' => 30
        ]);
        DB::table('saleproducts')->insert([ // 2
            'stockproduct_id' => 1,
            'name' => 'Alfajor 38g Blanco GUAYMALLEN Caja x40',
            'relacion_venta_stock' => 40,
            'porc_min' => 20,
            'porc_may' => 15
        ]);

        //--2---------------------------------------------
        DB::table('stockproducts')->insert([
            'name' => 'Alfajor 38g Negro GUAYMALLEN UNIDAD',
            'ivaaliquot_id' => 4,
            'costo' => 12.5
        ]);
        foreach(Sucursal::all() as $sucursal){
            DB::table('stocksucursals')->insert([
                'stockproduct_id' => 2,
                'sucursal_id' => $sucursal->id
            ]);
        }
        DB::table('saleproducts')->insert([ // 3
            'stockproduct_id' => 2,
            'name' => 'Alfajor 38g Negro GUAYMALLEN UNIDAD',
            'relacion_venta_stock' => 1,
            'porc_min' => 40,
            'porc_may' => 30
        ]);
        DB::table('saleproducts')->insert([ //4
            'stockproduct_id' => 2,
            'name' => 'Alfajor 38g Negro GUAYMALLEN Caja x40',
            'relacion_venta_stock' => 40,
            'porc_min' => 20,
            'porc_may' => 15
        ]);

        //--3---------------------------------------------
        DB::table('stockproducts')->insert([
            'name' => 'Chupetin Pelotitas LHERITIER PAQx50',
            'ivaaliquot_id' => 4,
            'costo' => 220
        ]);
        foreach(Sucursal::all() as $sucursal){
            DB::table('stocksucursals')->insert([
                'stockproduct_id' => 3,
                'sucursal_id' => $sucursal->id
            ]);
        }
        DB::table('saleproducts')->insert([ // 5
            'stockproduct_id' => 3,
            'name' => 'Chupetin Pelotitas LHERITIER PAQx50',
            'relacion_venta_stock' => 1,
            'porc_min' => 40,
            'porc_may' => 15
        ]);

        //--4---------------------------------------------
        DB::table('stockproducts')->insert([
            'name' => 'Heladitos Paisandu PAQx30',
            'ivaaliquot_id' => 4,
            'costo' => 130
        ]);
        foreach(Sucursal::all() as $sucursal){
            DB::table('stocksucursals')->insert([
                'stockproduct_id' => 4,
                'sucursal_id' => $sucursal->id
            ]);
        }
        DB::table('saleproducts')->insert([ // 6
            'stockproduct_id' => 4,
            'name' => 'Heladitos Paisandu PAQx30',
            'relacion_venta_stock' => 1,
            'porc_min' => 40,
            'porc_may' => 15
        ]);

        //--5---------------------------------------------
        DB::table('stockproducts')->insert([
            'name' => 'Harina de Trigo PAGx1Kg',
            'ivaaliquot_id' => 3,
            'costo' => 90
        ]);
        foreach(Sucursal::all() as $sucursal){
            DB::table('stocksucursals')->insert([
                'stockproduct_id' => 5,
                'sucursal_id' => $sucursal->id
            ]);
        }
        DB::table('saleproducts')->insert([ // 6
            'stockproduct_id' => 5,
            'name' => 'Harina de Trigo PAGx1Kg',
            'relacion_venta_stock' => 1,
            'porc_min' => 40,
            'porc_may' => 15
        ]);


        //--6---------------------------------------------
        DB::table('stockproducts')->insert([
            'name' => 'Bandeja 103 Micro UNIDAD',
            'ivaaliquot_id' => 4,
            'costo' => 5.5
        ]);
        foreach(Sucursal::all() as $sucursal){
            DB::table('stocksucursals')->insert([
                'stockproduct_id' => 6,
                'sucursal_id' => $sucursal->id
            ]);
        }
        DB::table('saleproducts')->insert([ // 3
            'stockproduct_id' => 6,
            'name' => 'Bandeja 103 Micro UNIDAD',
            'relacion_venta_stock' => 1,
            'porc_min' => 40,
            'porc_may' => 30
        ]);
        DB::table('saleproducts')->insert([ //4
            'stockproduct_id' => 6,
            'name' => 'ABandeja 103 Micro PAQx100',
            'relacion_venta_stock' => 100,
            'porc_min' => 25,
            'porc_may' => 15
        ]);

        //--7---------------------------------------------
        DB::table('stockproducts')->insert([
            'name' => 'Dulce de Leche EUREKA 10Kg UNIDAD',
            'ivaaliquot_id' => 4,
            'costo' => 2800
        ]);
        foreach(Sucursal::all() as $sucursal){
            DB::table('stocksucursals')->insert([
                'stockproduct_id' => 7,
                'sucursal_id' => $sucursal->id
            ]);
        }
        DB::table('saleproducts')->insert([ // 3
            'stockproduct_id' => 7,
            'name' => 'Dulce de Leche EUREKA 10Kg UNIDAD',
            'relacion_venta_stock' => 1,
            'porc_min' => 40,
            'porc_may' => 15
        ]);
        DB::table('saleproducts')->insert([ //4
            'stockproduct_id' => 7,
            'name' => 'Dulce de Leche EUREKA xKILO',
            'relacion_venta_stock' => 0.1,
            'porc_min' => 40,
            'porc_may' => 30
        ]);

        //--8---------------------------------------------
        DB::table('stockproducts')->insert([
            'name' => 'Tapita de Alfajor FANTOCHE CAJAx3,50Kg',
            'ivaaliquot_id' => 4,
            'costo' => 850
        ]);
        foreach(Sucursal::all() as $sucursal){
            DB::table('stocksucursals')->insert([
                'stockproduct_id' => 8,
                'sucursal_id' => $sucursal->id
            ]);
        }
        DB::table('saleproducts')->insert([ // 3
            'stockproduct_id' => 8,
            'name' => 'Tapita de Alfajor FANTOCHE CAJAx3,50Kg',
            'relacion_venta_stock' => 1,
            'porc_min' => 40,
            'porc_may' => 15
        ]);
        DB::table('saleproducts')->insert([ //4
            'stockproduct_id' => 8,
            'name' => 'Tapita de Alfajor FANTOCHE xKILO',
            'relacion_venta_stock' => 0.28571,
            'porc_min' => 40,
            'porc_may' => 30
        ]);

        //--9---------------------------------------------
        DB::table('stockproducts')->insert([
            'name' => 'Grana Azul DECORMAGIC',
            'ivaaliquot_id' => 4,
            'costo' => 240
        ]);
        foreach(Sucursal::all() as $sucursal){
            DB::table('stocksucursals')->insert([
                'stockproduct_id' => 9,
                'sucursal_id' => $sucursal->id
            ]);
        }
        DB::table('saleproducts')->insert([ // 3
            'stockproduct_id' => 9,
            'name' => 'Grana Azul DECORMAGIC PAQx1Kg',
            'relacion_venta_stock' => 1,
            'porc_min' => 40,
            'porc_may' => 15
        ]);
        DB::table('saleproducts')->insert([ //4
            'stockproduct_id' => 9,
            'name' => 'Grana Azul DECORMAGIC 100g',
            'relacion_venta_stock' => 0.1,
            'porc_min' => 40,
            'porc_may' => 30
        ]);

        //--10---------------------------------------------
        DB::table('stockproducts')->insert([
            'name' => 'Papel Prensa en rollo 40cm UNIDAD',
            'ivaaliquot_id' => 4,
            'costo' => 210,

            'is_stock_unitario_variable' => true,
            'stock_aproximado_unidad' => 6,
        ]);
        foreach(Sucursal::all() as $sucursal){
            DB::table('stocksucursals')->insert([
                'stockproduct_id' => 10,
                'sucursal_id' => $sucursal->id
            ]);
        }
        DB::table('saleproducts')->insert([ // 3
            'stockproduct_id' => 10,
            'name' => 'Papel Prensa en rollo 40cm UNIDAD',
            'relacion_venta_stock' => 1,
            'porc_min' => 40,
            'porc_may' => 15
        ]);

        //--11---------------------------------------------
        DB::table('stockproducts')->insert([
            'name' => 'Caramelos RICOMAS PAQx100',
            'ivaaliquot_id' => 4,
            'costo' => 170
        ]);
        foreach(Sucursal::all() as $sucursal){
            DB::table('stocksucursals')->insert([
                'stockproduct_id' => 11,
                'sucursal_id' => $sucursal->id
            ]);
        }
        DB::table('saleproducts')->insert([ // 3
            'stockproduct_id' => 11,
            'name' => 'Caramelos RICOMAS PAQx100',
            'relacion_venta_stock' => 1,
            'porc_min' => 40,
            'porc_may' => 15
        ]);
    }
}
