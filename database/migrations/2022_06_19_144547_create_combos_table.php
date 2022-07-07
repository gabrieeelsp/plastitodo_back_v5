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
            $table->decimal('desc_min', 10, 6);
            $table->decimal('desc_may', 10, 6);
            $table->decimal('precio_min', 10, 4);
            $table->decimal('precio_may', 10, 4);
            $table->boolean('is_enable')->default(true);
            $table->integer('precision')->default(4);
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
