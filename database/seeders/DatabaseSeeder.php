<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(IvaconditionSeeder::class);
        $this->call(EmpresaSeeder::class);
        $this->call(SucursalSeeder::class);

        $this->call(PaymentmethodSeeder::class);

        
        $this->call(IvaaliquotSeeder::class);
        $this->call(DoctypeSeeder::class);
        $this->call(ModelofactSeeder::class);


        $this->call(UserSeeder::class);

        $this->call(CajaSeeder::class);

        $this->call(StockproductSeeder::class);
        $this->call(ComboSeeder::class);
        $this->call(SaleSeeder::class);
    }
}
