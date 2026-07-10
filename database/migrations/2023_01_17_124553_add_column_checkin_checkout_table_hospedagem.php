<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCheckinCheckoutTableHospedagem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospedagem', function (Blueprint $table) {
            
            $table->Integer('checkin')->nullable();
            $table->Integer('checkout')->nullable();
        
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
