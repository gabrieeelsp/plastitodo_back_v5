<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->nullable();
            $table->string('punto_venta');

            $table->string('cae')->nullable();
            $table->string('cae_fch_vto')->nullable();

            $table->integer('id_afip_tipo');

            $table->integer('comprobanteable_id');
            $table->string('comprobanteable_type');

            $table->string('docnumber');

            $table->timestamps();

            $table->foreignId('modelofact_id')->constrained('modelofacts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('doctype_id')->constrained('doctypes')->onUpdate('cascade')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobantes');
    }
}
