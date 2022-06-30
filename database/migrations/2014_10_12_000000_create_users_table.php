<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->tinyInteger('role')->default(1);

            $table->enum('tipo', ['MINORISTA','MAYORISTA'])->default('MINORISTA');

            //--- Client -------
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('docnumber')->nullable();

            $table->boolean('is_fact_default')->default(false);

            $table->decimal('saldo', 15, 4)->default(0);

            $table->foreignId('ivacondition_id')->nullable()->constrained('ivaconditions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('doctype_id')->nullable()->constrained('doctypes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('modelofact_id')->nullable()->constrained('modelofacts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
