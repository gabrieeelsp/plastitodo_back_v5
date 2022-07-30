<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCombosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('desc_min', 4, 2);
            $table->decimal('desc_may', 4, 2);
            $table->decimal('precio_min', 10, 4);
            $table->decimal('precio_may', 10, 4);
            $table->boolean('is_enable')->default(true);
            $table->integer('precision_min')->default(2);
            $table->integer('precision_may')->default(2);

            $table->string('image1')->nullable();

            $table->string('image2')->nullable();

            $table->string('image3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('combos');
    }
}
