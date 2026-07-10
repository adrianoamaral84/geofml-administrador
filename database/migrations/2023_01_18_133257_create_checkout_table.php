<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_id')->unique();
            $table->foreign('usuario_id')->references('id')->on('user');

            $table->unsignedBigInteger('tarifa_id')->unique();
            $table->foreign('tarifa_id')->references('id')->on('tipo_tarifa_extra');

            $table->unsignedBigInteger('hospedagem_id')->unique();
            $table->foreign('hospedagem_id')->references('id')->on('hospedagem');

            $table->Integer('quantidade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkout');
    }
}
