<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NormalizarFinanceiroHospedagens extends Command
{
    protected $signature = 'hospedagens:normalizar-financeiro
                            {--apply : Grava as correções no banco. Sem esta opção, apenas simula}
                            {--id= : Processa somente uma hospedagem}
                            {--somente-checkout : Processa somente hospedagens com checkin igual a 2}';

    protected $description = 'Recalcula valor_pago e valor_restante usando os pagamentos confirmados';

    private $situacoesConfirmadas = [
        'CONCLUIDO',
        'PAGO',
        'PAGAMENTO_CONCLUIDO',
    ];

    public function handle()
    {
        if (!Schema::hasTable('hospedagem') || !Schema::hasTable('pagamento')) {
            $this->error('As tabelas hospedagem e pagamento precisam existir.');
            return 1;
        }

        $aplicar = (bool) $this->option('apply');
        $id = $this->option('id');

        $this->line($aplicar
            ? '<fg=red>MODO APLICAÇÃO: o banco será alterado.</>'
            : '<fg=yellow>MODO SIMULAÇÃO: nenhuma alteração será gravada.</>');

        $query = DB::table('hospedagem')
            ->select('id', 'valor', 'valor_pago', 'valor_restante', 'checkin')
            ->orderBy('id');

        if ($id !== null) {
            $query->where('id', $id);
        }

        if ($this->option('somente-checkout')) {
            $query->where('checkin', 2);
        }

        $analisados = 0;
        $divergentes = 0;
        $corrigidos = 0;
        $semPagamento = 0;

        $query->chunk(200, function ($hospedagens) use (
            $aplicar,
            &$analisados,
            &$divergentes,
            &$corrigidos,
            &$semPagamento
        ) {
            foreach ($hospedagens as $hospedagem) {
                $analisados++;

                $valorTotal = round((float) ($hospedagem->valor ?: 0), 2);

                $totalPagoConfirmado = round((float) DB::table('pagamento')
                    ->where('hospedagem_id', $hospedagem->id)
                    ->whereIn('tipo', ['diaria_inicial', 'pagamento_restante'])
                    ->whereIn('situacao', $this->situacoesConfirmadas)
                    ->sum('valor'), 2);

                if ($totalPagoConfirmado <= 0) {
                    $semPagamento++;
                    return $this->mostrarLinha(
                        $hospedagem,
                        $valorTotal,
                        0,
                        round((float) ($hospedagem->valor_pago ?: 0), 2),
                        round((float) ($hospedagem->valor_restante ?: 0), 2),
                        'SEM PAGAMENTO CONFIRMADO'
                    );
                }

                $novoValorPago = min($valorTotal, $totalPagoConfirmado);
                $novoValorRestante = max(0, round($valorTotal - $novoValorPago, 2));

                $valorPagoAtual = round((float) ($hospedagem->valor_pago ?: 0), 2);
                $valorRestanteAtual = round((float) ($hospedagem->valor_restante ?: 0), 2);

                $divergente = abs($valorPagoAtual - $novoValorPago) >= 0.01
                    || abs($valorRestanteAtual - $novoValorRestante) >= 0.01;

                if (!$divergente) {
                    return;
                }

                $divergentes++;
                $this->mostrarLinha(
                    $hospedagem,
                    $valorTotal,
                    $totalPagoConfirmado,
                    $novoValorPago,
                    $novoValorRestante,
                    $aplicar ? 'CORRIGINDO' : 'CORREÇÃO PROPOSTA'
                );

                if (!$aplicar) {
                    return;
                }

                DB::transaction(function () use (
                    $hospedagem,
                    $valorTotal,
                    $totalPagoConfirmado,
                    $novoValorPago,
                    $novoValorRestante
                ) {
                    $registro = DB::table('hospedagem')
                        ->where('id', $hospedagem->id)
                        ->lockForUpdate()
                        ->first();

                    if (!$registro) {
                        return;
                    }

                    if (Schema::hasTable('auditoria_financeira_hospedagem')) {
                        DB::table('auditoria_financeira_hospedagem')->insert([
                            'hospedagem_id' => $registro->id,
                            'valor_total' => $valorTotal,
                            'valor_pago_anterior' => $registro->valor_pago,
                            'valor_restante_anterior' => $registro->valor_restante,
                            'total_pagamentos_confirmados' => $totalPagoConfirmado,
                            'valor_pago_novo' => $novoValorPago,
                            'valor_restante_novo' => $novoValorRestante,
                            'motivo' => 'Normalização baseada na tabela pagamento',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    DB::table('hospedagem')
                        ->where('id', $registro->id)
                        ->update([
                            'valor_pago' => $novoValorPago,
                            'valor_restante' => $novoValorRestante,
                            'updated_at' => now(),
                        ]);
                });

                $corrigidos++;
            }
        });

        $this->line('');
        $this->table(
            ['Analisadas', 'Divergentes', 'Corrigidas', 'Sem pagamento confirmado'],
            [[$analisados, $divergentes, $corrigidos, $semPagamento]]
        );

        if (!$aplicar) {
            $this->info('Revise o resultado e depois execute novamente com --apply.');
        }

        return 0;
    }

    private function mostrarLinha(
        $hospedagem,
        $valorTotal,
        $totalPagoConfirmado,
        $novoValorPago,
        $novoValorRestante,
        $status
    ) {
        $this->line(sprintf(
            '[%s] Hospedagem %d | total %.2f | pagamentos %.2f | atual %.2f/%.2f | novo %.2f/%.2f',
            $status,
            $hospedagem->id,
            $valorTotal,
            $totalPagoConfirmado,
            (float) ($hospedagem->valor_pago ?: 0),
            (float) ($hospedagem->valor_restante ?: 0),
            $novoValorPago,
            $novoValorRestante
        ));
    }
}
