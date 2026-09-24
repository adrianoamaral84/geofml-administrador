<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHospedagemOrigemIdToHospedagemTable extends Migration
{
    public function up()
    {
        Schema::table('hospedagem', function (Blueprint $table) {
            $table->unsignedBigInteger('hospedagem_origem_id')
                ->nullable()
                ->after('id')
                ->index();
        });
    }

    public function down()
    {
        Schema::table('hospedagem', function (Blueprint $table) {
            $table->dropIndex(['hospedagem_origem_id']);
            $table->dropColumn('hospedagem_origem_id');
        });
    }
}
