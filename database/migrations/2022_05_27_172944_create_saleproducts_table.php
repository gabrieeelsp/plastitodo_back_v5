<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleproductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saleproducts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('relacion_venta_stock', 7, 4)->default(1);
            $table->decimal('porc_min', 20, 10)->default(0);
            $table->decimal('porc_may', 20, 10)->default(0);

            $table->foreignId('stockproduct_id')->constrained('stockproducts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saleproducts');
    }
}
