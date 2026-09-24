<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemanejamentoToHospedagemTable extends Migration
{
    public function up()
    {
        Schema::table('hospedagem', function (Blueprint $table) {
            $table->unsignedBigInteger('hospedagem_origem_id')->nullable()->after('id');
            $table->string('remanejamento_token', 64)->nullable()->index();
            $table->string('remanejamento_status', 20)->nullable()->index();
            $table->timestamp('remanejamento_aceito_at')->nullable();
        });

        Schema::create('hospedagem_auditoria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hospedagem_id');
            $table->unsignedBigInteger('admin_user_id')->nullable();
            $table->string('acao', 80);
            $table->text('detalhes')->nullable();
            $table->timestamps();

            $table->index('hospedagem_id');
            $table->index('admin_user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hospedagem_auditoria');

        Schema::table('hospedagem', function (Blueprint $table) {
            $table->dropIndex(['remanejamento_token']);
            $table->dropIndex(['remanejamento_status']);
            $table->dropColumn([
                'hospedagem_origem_id',
                'remanejamento_token',
                'remanejamento_status',
                'remanejamento_aceito_at',
            ]);
        });
    }
}
