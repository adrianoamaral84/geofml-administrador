<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospedagemAuditoriaTable extends Migration
{
    public function up()
    {
        Schema::create('hospedagem_auditoria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hospedagem_id')->index();
            $table->unsignedBigInteger('hospedagem_espelho_id')->nullable()->index();
            $table->unsignedBigInteger('administrador_id')->nullable()->index();
            $table->string('acao', 100);
            $table->text('detalhes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hospedagem_auditoria');
    }
}
