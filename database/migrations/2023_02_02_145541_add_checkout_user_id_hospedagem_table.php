<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckoutUserIdHospedagemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospedagem', function (Blueprint $table) {
            
            $table->unsignedBigInteger('checkout_user_id')->nullable()->unique();
            $table->foreign('checkout_user_id')->references('id')->on('user');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospedagem', function (Blueprint $table) {
            //
        });
    }
}
