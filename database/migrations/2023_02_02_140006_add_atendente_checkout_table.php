<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAtendenteCheckoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checkout', function (Blueprint $table) {
            
            $table->unsignedBigInteger('atendente_id')->nullable()->unique();
            $table->foreign('atendente_id')->references('id')->on('user');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checkout', function (Blueprint $table) {
            //
        });
    }
}
